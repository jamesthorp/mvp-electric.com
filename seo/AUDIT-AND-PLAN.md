# MVP Electric — SEO, AI Search & Lead-Gen Plan

Audit of the live site (mvp-electric.com, WordPress 6.8 + Elementor + Yoast), done 2026-10-30.
**Goal:** more calls, texts and quote requests from homeowners, contractors and businesses in
**Multnomah, Washington and Clackamas counties**. Not Salem, not Washington state.

---

## 1. What's hurting you right now (fix this week)

| # | Problem found on the live site | Why it matters | Fix |
|---|---|---|---|
| 1 | **Homepage has no H1 heading.** First heading is an H2 of the CCB/insurance line. | Google and AI tools use the H1 to work out what the page is about. | Make the hero headline an H1: "Licensed Electrician in Portland, Beaverton & Hillsboro". |
| 2 | **"Lorem ipsum" placeholder text** on the Homepage and About Us page (under "Bright Solutions for a Brighter Tomorrow"). | Looks unfinished to customers. Google sees it as low-quality filler. | Delete it or swap in the copy from `page-copy/homepage.md`. |
| 3 | **The homepage testimonials are template placeholders** ("Jake Mulligan", "Clara Hendricks", "Nathan Voss" with stock photos). Your **real** reviews (Sean M, Phil E, Joel K, Bob C, etc.) are only on /reviews. | The FTC's 2024 rule bans fake or made-up testimonials. Real reviews also convert better. | Replace them with 3 real reviews from /reviews. |
| 4 | **Homepage stats (4.9 rating, 60+ projects, 92% satisfaction, 10+ years)** | Fine if they're true. If they came with the template, they're a liability. | Check each number. Keep the true ones and delete the rest. |
| 5 | **No local-business schema.** Yoast only outputs a generic "Organization" with no phone, no service area, no hours and no CCB number. | This is the main data Google, Gemini and ChatGPT read to decide "this is an electrician serving Portland". | Paste `schema-electrician.html` (instructions inside). |
| 6 | **Title & meta description don't mention Portland.** Currently: "MVP Electric \| Credible Electrician Services". | The title is the biggest on-page ranking signal for "electrician Portland". | Use the titles in `page-copy/titles-and-meta.md`. |
| 7 | **No way to text you**, and the phone number isn't a big tappable button on mobile. | Most of these searches happen on phones. A text button often doubles lead volume for trades. | Add a sticky "Call / Text" bar (see `design/` mockups). Link: `sms:+15039226873`. |
| 8 | **Contact form asks for no phone number, city or job type.** It only has name, email and message. | You can't call leads back quickly or screen them by area. | Add Phone (required), City/ZIP, Service type (dropdown) and optional photo upload. |
| 9 | **No service-area pages** for the three counties or main cities. | "Electrician Beaverton" and "electrician Lake Oswego" are separate searches. You need a page that answers each one. | Create the 3 county pages in `page-copy/` (one page per county, not one per tiny town). |
| 10 | **Services you actually do aren't listed:** generator installs and interlocks, panel upgrades, troubleshooting, can lights, GFCI (these all come up in your real reviews). | People search for these much more than "lighting retrofits". | Add the "Panel Upgrades" and "Generator Installation" pages from `page-copy/`. |
| 11 | Small errors: "hearing **form** you" (contact page), "timely **manor**" (a review; leave it, it's the customer's words), Solar page says "Installing a home or business **charging station** can increase the value…", "safe, efficient, and efficient" (homepage), footer "Copyright 2024", "All Right Reserved". | Polish and trust. | Fix the typos and set the footer year to 2026. |
| 12 | Only 1 of 16 homepage images has alt text. | Accessibility, plus image search. | Add descriptive alt text, e.g. "200-amp panel upgrade in Beaverton home". |
| 13 | The Instagram icon links to the generic `facebook.com` homepage. | Broken trust signal. | Point it at your real Instagram account, or remove it. |
| 14 | No `/llms.txt`. | A small, cheap signal that helps AI assistants summarize you correctly. | Upload `llms.txt` to the site root. |

---

## 2. The biggest lever: your Google Business Profile (GBP)

For "electrician near me" and "electrician Portland", the **map pack** (the 3 businesses with
the map) gets most of the calls, and **Gemini / Google AI Overviews pull directly from GBP**.
The website supports the GBP. It doesn't replace it.

- [ ] Claim or verify the GBP. Set it up as a **service-area business** (hide your home address).
- [ ] Primary category: **Electrician**. Secondary categories: *Electrical installation service*,
      *Lighting contractor*, *Solar energy contractor*, *EV charging station contractor*
      (pick the ones Google offers that you really do).
- [ ] Service areas: add the cities from the list in `schema-electrician.html` (up to 20).
      Leave out Salem and Vancouver WA.
- [ ] Add every service with a 1–2 sentence description, including panel upgrades, generators,
      EV chargers and troubleshooting.
- [ ] Turn on **messaging** and set the website link to the homepage. Set the appointment link
      to /contact-us/.
- [ ] Upload **2–4 real job photos every week** (panels, EV chargers, generator inlets, before
      and after). Fresh photos are a strong ranking signal.
- [ ] Post a weekly "update" (a finished job, with the city named).
- [ ] **Reviews: this is the #1 ranking factor you control.** Text every finished customer your
      GBP review link. Ask them to mention the service and their city
      ("panel upgrade in Tigard"). Reply to every review.
- [ ] Use the exact same name / phone / website everywhere (NAP consistency):
      **MVP Electric · (503) 922-6873 · https://mvp-electric.com**

## 3. Get listed where AI assistants look

AI answers (Gemini, ChatGPT, Copilot, Perplexity, Siri) mostly come from search indexes and
business directories, not your site alone.

- [ ] **Google Search Console**: verify the site and submit `https://mvp-electric.com/sitemap_index.xml`.
- [ ] **Bing Webmaster Tools** + **Bing Places for Business**. ChatGPT and Copilot rely heavily on Bing.
      Bing Webmaster Tools can import from Search Console in one click.
- [ ] **Apple Business Connect**, used by Siri and Apple Maps.
- [ ] Yelp, Nextdoor (big for local trades), Angi, BBB, Houzz. Use the same NAP on each, and link back to your site.
- [ ] Make sure your **Oregon CCB #253767** and electrical contractor license are easy to find on
      the site (AI tools like to cite licensing for trades). Add your Oregon BCD electrical license
      number next to the CCB if you have one.
- [ ] Write content that answers questions directly. AI assistants quote clear Q&A. The county
      pages and service pages in `page-copy/` include FAQ sections for that reason.

## 4. Site structure to aim for

```
Home  (Electrician in Portland Metro — H1, call/text, real reviews, service area map)
├── Services
│   ├── Electrical Repair & Troubleshooting   (rename "General Electrical Services")
│   ├── Panel Upgrades & Service Changes       (NEW)
│   ├── Generator Installation & Interlocks    (NEW)
│   ├── EV Charger Installation
│   ├── Lighting & LED Retrofits
│   ├── Solar & Battery Backup
│   └── New Construction & Remodels
├── Service Areas
│   ├── Washington County Electrician          (NEW)
│   ├── Clackamas County Electrician           (NEW)
│   └── Multnomah County / Portland Electrician (NEW)
├── Reviews
├── About (James, license, insurance, photo of you and the truck)
└── Contact / Free Estimate
```

Keep existing URLs (don't rename slugs) so you don't lose current rankings. If you do rename
one, add a 301 redirect (Yoast Premium or the free "Redirection" plugin).

## 5. Conversion basics (turning visitors into calls)

- Phone number and **Call / Text** buttons above the fold, plus a sticky bar on mobile.
- Use "Free Estimate" in place of "Get Free Quote" / "Learn More". It's one clear action.
- Put a photo of **you** and your work truck on the homepage and About page. For a small
  contractor, the real person is the selling point (most of your reviews mention James by name).
- Put "Licensed · Bonded · Insured · CCB #253767" right under the headline.
- Track calls: in GA4, mark clicks on `tel:` and `sms:` links as key events.
  Google Tag Manager or the Site Kit plugin can do this without code.

## 6. Speed

The site loads Elementor, Elementor Pro, ElementsKit and MetForm. Each adds CSS and JS.
- Remove ElementsKit and MetForm if Elementor Pro's form/widgets cover what you use.
- Convert big JPG/PNG images to WebP, at 1600px wide max.
- Use a caching plugin (e.g. LiteSpeed Cache if your host supports it, otherwise WP Super Cache).
- Check results at https://pagespeed.web.dev. Aim for a mobile score of 80+.

## 7. 90-day checklist

**Week 1:** items 1–8 above, plus schema, GBP cleanup, Search Console and Bing.
**Weeks 2–4:** publish the 3 county pages and the Panel Upgrade and Generator pages. Pick a design option and start the refresh.
**Ongoing:** 2 review requests a week (minimum), 2–4 GBP photos a week, 1 GBP post a week, and 1 new
job write-up a month ("EV charger install in Happy Valley"), each with photos.
