#!/usr/bin/env python3
"""Command-line tool for editing mvp-electric.com through the WordPress REST API.

Login: set WP_USER and WP_APP_PASSWORD (a WordPress Application Password) as environment
variables. WP_URL defaults to https://mvp-electric.com.

    python3 tools/wp.py pages                    list pages
    python3 tools/wp.py pull [slug ...]          save pages to site-snapshots/ (all pages if no slug given)
    python3 tools/wp.py push <slug>              upload site-snapshots/<slug>/elementor.json (takes a backup first)
    python3 tools/wp.py seo <slug> --title T --desc D
    python3 tools/wp.py create <slug> --title T [--copy-from SLUG]   make a new DRAFT page
    python3 tools/wp.py status <slug> draft|publish
    python3 tools/wp.py purge                    clear Elementor and LiteSpeed caches
    python3 tools/wp.py setup                    one-time install of the connector (needs an admin login)

Every pull and push writes to site-snapshots/, so git history doubles as an undo log.
"""
import argparse
import base64
import datetime
import json
import os
import sys
import urllib.error
import urllib.request
from pathlib import Path

BASE = os.environ.get("WP_URL", "https://mvp-electric.com").rstrip("/")
ROOT = Path(__file__).resolve().parent.parent
SNAPSHOTS = ROOT / "site-snapshots"
BACKUPS = SNAPSHOTS / "_backups"


def api(method, path, body=None):
    headers = {"Accept": "application/json", "User-Agent": "mvp-claude-connector/1.0"}
    user, pw = os.environ.get("WP_USER"), os.environ.get("WP_APP_PASSWORD")
    if user and pw:
        token = base64.b64encode(f"{user}:{pw.replace(' ', '')}".encode()).decode()
        headers["Authorization"] = f"Basic {token}"
    data = None
    if body is not None:
        data = json.dumps(body).encode()
        headers["Content-Type"] = "application/json"
    req = urllib.request.Request(f"{BASE}/wp-json{path}", data=data, headers=headers, method=method)
    try:
        with urllib.request.urlopen(req, timeout=60) as r:
            return json.loads(r.read() or b"null")
    except urllib.error.HTTPError as e:
        sys.exit(f"{method} {path} failed: HTTP {e.code}\n{e.read().decode(errors='replace')[:1000]}")


def require_auth():
    if not (os.environ.get("WP_USER") and os.environ.get("WP_APP_PASSWORD")):
        sys.exit("WP_USER and WP_APP_PASSWORD are not set. Add them in the cloud environment settings.")


def page_by_slug(slug):
    pages = api("GET", f"/wp/v2/pages?slug={slug}&status=publish,draft,private,pending&context=edit")
    if not pages:
        sys.exit(f"No page with slug '{slug}'")
    return pages[0]


def all_pages():
    authed = os.environ.get("WP_USER") and os.environ.get("WP_APP_PASSWORD")
    extra = "&status=publish,draft,private,pending&context=edit" if authed else ""
    out, n = [], 1
    while True:
        batch = api("GET", f"/wp/v2/pages?per_page=100&page={n}{extra}")
        out += batch
        if len(batch) < 100:
            return out
        n += 1


def save_page(page, folder):
    folder.mkdir(parents=True, exist_ok=True)
    meta = page.get("meta", {})
    raw = meta.get("_elementor_data") or "[]"
    (folder / "elementor.json").write_text(json.dumps(json.loads(raw), indent=1, ensure_ascii=False) + "\n")
    info = {
        "id": page["id"],
        "slug": page["slug"],
        "status": page["status"],
        "link": page["link"],
        "title": page["title"].get("raw", page["title"].get("rendered")),
        "template": page.get("template"),
        "seo_title": meta.get("_yoast_wpseo_title", ""),
        "seo_description": meta.get("_yoast_wpseo_metadesc", ""),
        "modified": page.get("modified"),
    }
    (folder / "page.json").write_text(json.dumps(info, indent=1, ensure_ascii=False) + "\n")


def backup(page):
    stamp = datetime.datetime.now().strftime("%Y%m%d-%H%M%S")
    save_page(page, BACKUPS / f"{page['slug']}-{stamp}")


def cmd_pages(a):
    for p in all_pages():
        print(f"{p['id']:>6}  {p['status']:<8} {p['slug']:<40} {p['title'].get('raw') or p['title'].get('rendered')}")


def cmd_pull(a):
    require_auth()
    pages = [page_by_slug(s) for s in a.slugs] if a.slugs else all_pages()
    for p in pages:
        save_page(p, SNAPSHOTS / p["slug"])
        print("pulled", p["slug"])


def cmd_push(a):
    require_auth()
    page = page_by_slug(a.slug)
    backup(page)
    layout = json.loads((SNAPSHOTS / a.slug / "elementor.json").read_text())
    api("POST", f"/wp/v2/pages/{page['id']}", {"meta": {
        "_elementor_data": json.dumps(layout, ensure_ascii=False, separators=(",", ":")),
        "_elementor_edit_mode": "builder",
    }})
    save_page(page_by_slug(a.slug), SNAPSHOTS / a.slug)
    print("pushed", a.slug, "->", page["link"])


def cmd_seo(a):
    require_auth()
    page = page_by_slug(a.slug)
    backup(page)
    meta = {}
    if a.title is not None:
        meta["_yoast_wpseo_title"] = a.title
    if a.desc is not None:
        meta["_yoast_wpseo_metadesc"] = a.desc
    api("POST", f"/wp/v2/pages/{page['id']}", {"meta": meta})
    save_page(page_by_slug(a.slug), SNAPSHOTS / a.slug)
    print("updated SEO for", a.slug)


def cmd_create(a):
    require_auth()
    body = {"slug": a.slug, "title": a.title, "status": "draft"}
    if a.copy_from:
        src = page_by_slug(a.copy_from)
        body["template"] = src.get("template") or ""
        body["meta"] = {
            "_elementor_data": src["meta"].get("_elementor_data") or "[]",
            "_elementor_edit_mode": "builder",
            "_elementor_template_type": "wp-page",
        }
    page = api("POST", "/wp/v2/pages", body)
    save_page(api("GET", f"/wp/v2/pages/{page['id']}?context=edit"), SNAPSHOTS / page["slug"])
    print("created DRAFT", page["slug"], "preview:", f"{BASE}/?page_id={page['id']}&preview=true")


def cmd_status(a):
    require_auth()
    page = page_by_slug(a.slug)
    api("POST", f"/wp/v2/pages/{page['id']}", {"status": a.status})
    print(a.slug, "is now", a.status)


def cmd_purge(a):
    require_auth()
    print(api("POST", "/mvp-cc/v1/purge", {}))


CONNECTOR_NAME = "MVP Claude Connector"
CONNECTOR_FILE = ROOT / "wordpress-plugin" / "mvp-claude-connector" / "mvp-claude-connector.php"


def cmd_setup(a):
    """One-time install of the connector, using an Administrator Application Password.

    WordPress's API can only install plugins from wordpress.org, so this installs the free
    "Code Snippets" plugin and adds the connector code to it as an always-on snippet.
    """
    require_auth()
    me = api("GET", "/wp/v2/users/me?context=edit")
    if "administrator" not in me.get("roles", []):
        sys.exit(f"'{me.get('slug')}' is not an Administrator. Setup needs an admin Application Password.")

    plugins = {p["plugin"]: p for p in api("GET", "/wp/v2/plugins")}
    if any(k.startswith("mvp-claude-connector/") and v["status"] == "active" for k, v in plugins.items()):
        print("Connector plugin is already active. Nothing to do.")
        return

    cs = plugins.get("code-snippets/code-snippets")
    if cs is None:
        print("Installing Code Snippets from wordpress.org…")
        api("POST", "/wp/v2/plugins", {"slug": "code-snippets", "status": "active"})
    elif cs["status"] != "active":
        print("Activating Code Snippets…")
        api("POST", "/wp/v2/plugins/code-snippets/code-snippets", {"status": "active"})

    code = CONNECTOR_FILE.read_text().replace("<?php", "", 1).strip()
    existing = [s for s in api("GET", "/code-snippets/v1/snippets") if s.get("name") == CONNECTOR_NAME]
    body = {"name": CONNECTOR_NAME, "code": code, "scope": "global", "active": True,
            "desc": "Lets Claude edit Elementor pages and Yoast SEO fields over the REST API. Deactivate to cut off editing."}
    if existing:
        api("POST", f"/code-snippets/v1/snippets/{existing[0]['id']}", body)
        print("Updated the connector snippet.")
    else:
        api("POST", "/code-snippets/v1/snippets", body)
        print("Added the connector snippet.")

    home = api("GET", "/wp/v2/pages/14?context=edit")
    if "_elementor_data" in home.get("meta", {}):
        print("Setup complete: Elementor layouts are now editable.")
    else:
        sys.exit("The snippet was saved, but Elementor data still isn't visible. Check Snippets in wp-admin.")


def main():
    p = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    sub = p.add_subparsers(dest="cmd", required=True)
    sub.add_parser("pages").set_defaults(fn=cmd_pages)
    s = sub.add_parser("pull"); s.add_argument("slugs", nargs="*"); s.set_defaults(fn=cmd_pull)
    s = sub.add_parser("push"); s.add_argument("slug"); s.set_defaults(fn=cmd_push)
    s = sub.add_parser("seo"); s.add_argument("slug"); s.add_argument("--title"); s.add_argument("--desc"); s.set_defaults(fn=cmd_seo)
    s = sub.add_parser("create"); s.add_argument("slug"); s.add_argument("--title", required=True); s.add_argument("--copy-from"); s.set_defaults(fn=cmd_create)
    s = sub.add_parser("status"); s.add_argument("slug"); s.add_argument("status", choices=["draft", "publish"]); s.set_defaults(fn=cmd_status)
    sub.add_parser("purge").set_defaults(fn=cmd_purge)
    sub.add_parser("setup").set_defaults(fn=cmd_setup)
    a = p.parse_args()
    a.fn(a)


if __name__ == "__main__":
    main()
