# MVP Electric website: working rules

The live site (https://mvp-electric.com) is WordPress + Elementor Pro + Yoast SEO + LiteSpeed Cache.
Edit it with `tools/wp.py`, which uses the WordPress REST API. Login comes from the `WP_USER` and
`WP_APP_PASSWORD` environment variables (a WordPress Application Password). The
`wordpress-plugin/mvp-claude-connector` plugin must be active on the site for Elementor and Yoast edits.

**First run / "set up the website connection":** run `python3 tools/wp.py setup` (installs the
connector through the free Code Snippets plugin; needs an Administrator login), then
`python3 tools/wp.py pull` to back up every page, commit and push. If `WP_USER`/`WP_APP_PASSWORD`
are missing, point James to `wordpress-plugin/SETUP.md`. Never ask him to paste a password into chat.

James (the owner) gives instructions in plain English. Follow this every time:

1. `python3 tools/wp.py pull <slug>` before changing a page, so you edit the live version.
2. Edit `site-snapshots/<slug>/elementor.json` (Elementor's layout: nested sections, columns and
   widgets; text lives in `settings`, e.g. `title`, `editor`, `text`). Change only what was asked.
3. Show James a short before/after summary of the change. For anything bigger than a text or SEO
   tweak, get his OK before pushing.
4. `python3 tools/wp.py push <slug>` (it saves a backup to `site-snapshots/_backups/` first), then
   fetch the live page and confirm the change shows up.
5. New pages are always created as **drafts** (`create`). Publish only after James approves the preview.
6. Commit the snapshots and push, so git history is the undo log.

To undo: copy the `elementor.json` from the relevant `_backups/` folder (or an older git commit)
back into `site-snapshots/<slug>/` and push it.

Never publish fake reviews, ratings or stats. The service area is Multnomah, Washington and Clackamas
counties only: no Salem, no Washington state. Phone/text: (503) 922-6873. CCB #253767. Oregon electrical contractor license #C2008.
Confirmed true and OK to say: 10+ years of experience, licensed/bonded/insured, free estimates, permit
included in bids, usually same-day replies. James installs solar himself (keep solar as a full service). Generators: everything from interlock kits and inlets to whole-home standby generators. Advertise panel upgrades, commercial work and new construction for GCs. Takes all job sizes (no exclusions); Hours: Mon–Fri 8am–5pm; Saturday available for emergencies (say "Saturday emergency service available"; never "24/7"). The 4.9 / 60+ / 92% stats and the Jake Mulligan, Clara
Hendricks and Nathan Voss testimonials were made up by a previous designer: never use them.
The SEO plan and the copy waiting to go live are in `seo/`. Design options are in `design/`.
