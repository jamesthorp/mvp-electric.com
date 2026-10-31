# One-time setup: let Claude edit the website (about 3 minutes, 2 steps)

## Step 1: Make a password just for Claude (in WordPress)
1. Log in to WordPress the way you normally do (mvp-electric.com/wp-admin).
2. In the left menu click **Users → Profile**.
3. Scroll near the bottom to **Application Passwords**. Type `Claude` in the name box and click **Add New Application Password**.
4. Copy the password it shows (it looks like `abcd EFGH 1234 ijkl …`). It is shown only once.

This isn't your real WordPress password. It only works for this connection, and you can switch it off any time by clicking **Revoke** on the same screen.

## Step 2: Save it in Claude (not in the chat)
1. In Claude, click the environment name at the top of this session, then **Edit**.
2. Add two entries (as environment variables or secrets):
   - `WP_USER` = the username you log in to WordPress with
   - `WP_APP_PASSWORD` = the password you copied in Step 1
3. Save, then start a **new session** on this project.

## Then just say: "Set up the website connection."
Claude will install the connector by itself, save a copy of every page as a backup, and confirm it
works. After that you can give plain instructions like "remove the Lorem ipsum from the homepage."

### Want Claude to have less access later?
Once everything is working, you can create a separate `claude-editor` user with the **Editor** role,
make an Application Password for it, and swap it into Step 2. Then revoke the admin one.
