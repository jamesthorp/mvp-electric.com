# One-time setup: let Claude edit the website directly (about 10 minutes)

## 1. Back up the site
In your hosting dashboard, run a full backup. Or install the free **UpdraftPlus** plugin and click "Backup Now".

## 2. Create a dedicated WordPress user for Claude
WordPress admin → **Users → Add New User**
- Username: `claude-editor`
- Email: a separate address (e.g. james+claude@mvp-electric.com)
- Role: **Editor** (not Administrator; Editors can change pages but can't install plugins or change settings)

## 3. Create an Application Password for that user
Log in as (or edit) `claude-editor` → **Users → Profile** → scroll to **Application Passwords**
→ Name: `Claude` → **Add New Application Password**. Copy the password it shows (it appears only once).
To cut off access at any time, click **Revoke** here.

## 4. Install the connector plugin
WordPress admin → **Plugins → Add New → Upload Plugin** → choose
`wordpress-plugin/mvp-claude-connector.zip` from this repo → **Install** → **Activate**.
(It lets the Editor account change Elementor layouts and Yoast SEO fields over the API, and clears
the cache after each change. It doesn't add anything visible to your site.)

## 5. Store the login in your Claude cloud environment (not in chat)
In Claude, open the cloud environment menu in the session's title bar → **Edit**, and add:
- `WP_USER` = `claude-editor`
- `WP_APP_PASSWORD` = the password from step 3

Then start a **new session** on this repo so it picks them up.

## 6. Test it
In the new session say: "Pull all the pages from the website." If Claude lists the pages it saved, you're set.
After that, just give instructions, e.g.:
- "Remove the Lorem ipsum text from the homepage."
- "Swap the homepage reviews for the real ones on the Reviews page."
- "Create the Washington County page as a draft so I can look at it."
