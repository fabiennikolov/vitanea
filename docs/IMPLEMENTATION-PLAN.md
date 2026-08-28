# Vitanea — Online Store

## Context

**ВИТАНЕА ООД** is a family-run Plovdiv producer of fruit-based functional drinks — aronia nectars,
syrups, juices, teas and pectins. They sell today through an OpenCart 3 install at
`vitanea.com/shop/`. This project replaces it.

The brief is explicitly **not** "a nicer shop for syrups and juices." Four pillars come from the
client's own words, and every decision below traces to one of them:

1. **Category ownership** — *"единственият производител на бг пазара на функционални напитки."*
2. **Nothing added** — competitors fortify with vitamins and minerals; Vitanea does not.
   *"Функционалността на нашите продукти идва от полезността на самия плод."*
   **This is the sharpest differentiator and the hardest to express in a webshop** — it is a claim
   about an *absence*, and a store's entire visual grammar is built around addition.
3. **Real fruit** — *"Ние произвеждаме всичко от истински плодове."*
4. **Scientific lineage** — *"аронията става известна в България, благодарение на научните
   изследвания на проф. Христо Крачанов."* And aronia is central for a stated reason:
   *"Заради полезните ѝ качества аронията е плодът, който влагаме в почти всички наши продукти."*
   That sentence is why **Защо арония?** earns its own nav item — the fruit is the through-line of
   the range, not one flavour among several.

The brief tells us **what to emphasise**. Their own За нас page tells us how much material there is
to do it with: Vitanea was *"създадена през 2003 г. от проф. д-р Христо Крачанов"* — he founded the
company — and every product is made *"по оригиналната технология на проф. Христо Крачанов."*
So the science lineage is not a reputation the brand cites from outside; it is the company's own
origin. That makes pillar 4 considerably easier to build a page around.

### Intended outcome

A Bulgarian storefront where the identity is load-bearing rather than decorative: "nothing added"
is provable on every product page, the Krachanov lineage has a real home, and aronia is presented
as a subject rather than a flavour.

---

## How the two mockups are used

They serve different jobs — this was clarified by the client and supersedes any earlier reading.

| Mockup | Job |
|---|---|
| `public/images/hero_section_design_mock.jpg` | **The hero, and the visual language for the whole site** — palette, typography, restraint |
| `public/images/design_mock_v2.jpg` | **Layout and section structure for the pages** — what blocks exist and how they compose |

**v2 gives the shapes. v1 gives every colour.** v2's own hero is not used; v1 wins there.
v2's discount ribbon and countdown timer are dropped — they signal commodity competition, which
works against pillar 1.

From v1, carried site-wide: eyebrow `ПРИРОДА × НАУКА`, headline `Рестартирай отвътре.`, a primary
pill CTA beside a link-style secondary, organic blob shapes, spec pills, and the nav —
**Продукти · Серии · Защо арония? · Науката · За нас** — which already encodes all four pillars.

### Homepage composition — v2's skeleton in v1's palette

| v2 section | Vitanea translation | Ground |
|---|---|---|
| Hero | v1 hero, unchanged | cream `#F7EDE4` |
| "Our Drinks Menu" — 3 cards | Сокове · Нектари · Сиропи, product on a plum blob | cream |
| 6-item benefit grid | The four pillars + production principles from За нас | sage `#E0DAC5` |
| "Deal Of The Day" | **Продукт във фокус** — same layout, composition panel replaces the countdown | cream + berry |
| 4.9 rating panel | **Науката зад аронията** — Krachanov and cited research (they have zero reviews site-wide, so the slot cannot be filled as designed) | plum `#3A233B` |
| Gallery grid | **Производство** — the Plovdiv factory, harvest, fruit | cream |
| Newsletter | As designed | sage |
| Footer | As designed; payment badges only once a gateway exists | plum |

---

## Company profile

Compiled from the shop's privacy policy, the Trade Register, and press. Feeds the footer, contact
page, legal pages and the За нас / Науката content.

### Legal entity

| Field | Value |
|---|---|
| Legal name | **ВИТАНЕА ООД** (VITANEA OOD) |
| ЕИК / BULSTAT | **115796742** |
| VAT № | Presumably `BG115796742` — ⚠️ not published anywhere; confirm with client |
| Legal form | ООД, capital 5,000 лв |
| Trade Register entry | 22.08.2008 — ⚠️ this is the mandatory re-registration; the company dates itself to **2003** |
| Registered seat | гр. Пловдив 4000, р-н Централен, ул. „Д-р Г. М. Димитров" № 20, ет. 1 |
| Factory / shop contact | гр. Пловдив, ул. „Рогошко шосе" |
| Legacy office | бул. „Васил Априлов" 97, Пловдив |

⚠️ **Three addresses are in circulation.** The impressum needs a client decision on which is
official for e-commerce purposes.

### Contact

| | |
|---|---|
| Phone | +359 32 644 016 · +359 884 524 995 |
| Email | info@vitanea.com · blagovesta.krachanova@vitanea.com — ⚠️ **no email is published on the shop at all today** |
| People | д-р Благовеста Крачанова +359 887 853 621 · агр. Роберто Тонеро +359 888 059 010 |
| Facebook | `facebook.com/ARONIAJUICES` (linked in footer) — ⚠️ a second page `facebook.com/VitaneaJuices` also exists; needs consolidation |
| Instagram | `@vitanea_aronia` |

### Company background

- **Family company founded 2003** by проф. д-р Христо Крачанов and доц. Мария Крачанова.
  Run today by their daughter **д-р Благовеста Крачанова**. The factory was designed by the other
  daughter, architect **Милена Крачанова**.
- **1984** — after six years of research, the first dietetic aronia beverages (syrups).
  **1998** — natural juices from anthocyanin-rich fruit: aronia, blackberry, strawberry.
- **Factory**, Пловдив, ул. „Рогошко шосе": built from May 2017, 1,351 m², ~1.3–1.75 M лв
  (sources disagree), capacity 262 t/year. Award-nominated — Archinova, Building of the Year.
- **Zero-waste**: pomace becomes tea or animal feed. Only fresh or frozen fruit, never concentrate.
  No preservatives, synthetic colours, synthetic flavours or emulsifiers. Cold-pressed.
- **Revenue** 919,000 лв (2020) → 1,342,000 лв (2021), +46%. Forbes Bulgaria cites **~30% export**.
- **Retail**: Фантастико, 345, Дар; previously BILLA and Kaufland — ⚠️ 2020 data, verify.

### проф. д.х.н. Христо Крачанов — content for the Науката page

Born **29 September 1931, Plovdiv**. ВХТИ София 1954; at ВИХВП Пловдив from 1956.
Docent 1967; **Head of the Department of Organic Chemistry and Biochemistry, УХТ Пловдив,
1967–1992**; doctoral thesis on pectins 1982; **full professor 1984**. From 1981 headed the
Laboratory of Biologically Active Carbohydrates (BAS, Plovdiv).

**150+ scientific papers · 17 supervised dissertations · 40 inventions · 9 foreign patents**
(2 US, 2 Germany, Switzerland, France, Yugoslavia). Entered in the **Златна книга на българските
откриватели и изобретатели**. Known nationally as **„Бащата на аронията."** BNT: *"човекът, който
превърна аронията в земеделска култура у нас."*

His contribution was a **juice-extraction technology that preserves aronia's bioactive compounds** —
which is precisely the "function comes from the fruit" pillar, stated as engineering.

A usable detail: after ~40 years working with aronia he says he has personally eaten
*"не повече от 200–300 грама"* of it, because it is so astringent — which is why he engineered
drinkable formats in the first place.

> ⚠️ **Born 1931 — he would be 94.** No obituary found, and living status could not be confirmed.
> **Verify with the family before writing in past or present tense.** This blocks final copy on the
> Науката and За нас pages, nothing else.

A further asset: **Denev, Kratchanova et al. (2018),** *Journal of Chemistry* 2018:9574587 —
peer-reviewed analysis of 23 Bulgarian-grown aronia samples, **co-authored by the founder's wife.**
A citable evidence base one degree from the brand. Note it analysed experimentally prepared juices,
**not Vitanea products** — do not imply otherwise.

---

## Design system

### Palette — sampled from mock v1, used site-wide

| Token | Hex | Role |
|---|---|---|
| `--color-cream-50` | `#F7EDE4` | Page background |
| `--color-sage-200` | `#E0DAC5` | Alternating section panels |
| `--color-plum-950` | `#1E0A25` | Headlines, body ink |
| `--color-plum-900` | `#281D2B` | Primary button fill |
| `--color-plum-800` | `#3A233B` | Dark bands, decorative blobs |
| `--color-berry-600` | `#C10024` | Sole accent — prices, focus rings |
| `--color-aronia-950` | `#490206` | Deep product tone |

Replaces the stock shadcn neutral greyscale in `resources/css/app.css`, which carries no brand
colour. Berry is the only saturated hue — used once per view it reads as emphasis.

### Typography — Cyrillic is a hard requirement

`vite.config.ts` self-hosts through `bunny(…)` and `laravel-vite-plugin` defaults to
`subsets: ['latin']`, so **a font added without an explicit `cyrillic` subset renders every
Bulgarian character in a system fallback.** Instrument Sans and Instrument Serif are ruled out
entirely — no Cyrillic glyphs at all — as are the rounded display faces Fredoka, Baloo 2 and
Quicksand.

```ts
fonts: [
    bunny('Nunito', { weights: [400, 500, 600, 700, 800], subsets: ['latin', 'latin-ext', 'cyrillic'] }),
    bunny('IBM Plex Mono', { weights: [400, 500], subsets: ['latin', 'latin-ext', 'cyrillic'] }),
],
```

`--font-display` and `--font-sans` are both Nunito — a rounded face with a drawn Cyrillic cut,
separated by weight rather than by family. No new dependency; fonts stay self-hosted.

Type rules: `font-bold` on display headlines, `font-semibold` on card and panel titles; no `leading-*` on
headings; `tracking-tight` above `text-xl`; `text-balance` on headings, `text-pretty` on paragraphs;
`max-w-[*ch]` per element, never on the heading-group wrapper; `tabular-nums` on every price.

### The "nothing added" device

A **composition panel** on every product page, condensed on cards, and the homepage's
Продукт във фокус block:

```
СЪСТАВ                        Типична функционална напитка
─────────────────────         ─────────────────────
Сок от арония ......  100%    Плодова основа
                              + Витамин C (добавен)
Нищо друго.                   + Цинк (добавен)
                              + Консервант
```

Two things make it work: **it is large** — it takes the slot a discount ribbon would occupy — and
**it is data, not copy.** `product_ingredients.is_added` is a real column, so "nothing added" is
queryable and cannot drift out of sync with the product.

### Layout

Two-element section pattern throughout (outer: background + vertical padding; inner:
`max-w-* mx-auto` + horizontal padding) so edges align down the page. One shared column gap, one
radius per container level, one primary and one link-style secondary button everywhere.
Left-aligned heading groups by default; centred only for the hero and closing CTA.

---

## Current site audit

Crawled `vitanea.com/shop/` — OpenCart 3.0.x, Bulgarian only, **28 products (27 real + 1 test)**.
EUR is already the primary currency with BGN shown parenthetically at 1.95583.

### Catalog

| Category | path | Products |
|---|---|---|
| Сокове | 63 | 1 |
| Нектари | 20 | 6 — Без захар 1л (2), Със захар 1л (2), Детска серия (2) |
| Сиропи | 18 | 16 — split across six **dilution-ratio** subcategories |
| Пектини | 67 | 2 |
| Чайове | 17 | 2 (+1 test) |
| За деца | 60 | cross-listing of the Детска серия nectars |
| Промоции | 61 | empty |

Prices €1.44–€9.45. **14 of 28 are Разпродаден**, including the flagship Сок от арония 100%,
both teas, both pectins and the entire children's line.

Images: `image/catalog/edit photos/` (most), `image/catalog/out of stock/` (pectins + product 86),
and `image/catalog/` with a **leading space and Cyrillic filenames** for products 94–95. Masters are
1200×1200 for the older set, 600×600 for the two newest. **Every product has exactly one image** —
no galleries anywhere.

### Problems to fix in the rebuild

1. **A live test product is publicly purchasable** — `product_id=96`, "тестови продукт", €10.00,
   in stock, in Чайове, described "For test purposes of EUR functionality". Must not migrate.
2. **Demo data leftovers** — Нектари's description is `"Example of category description text"`;
   product 52's Спецификация tab contains `Processor / Clockspeed: 100mhz`.
3. **No real SKUs.** The `model` field is relabelled `Вид продукт` and holds a format string.
4. **Duplicate names across sizes** — four products named "Арония плодов сироп със захар"
   (ids 72, 86, 88), two named "Ягода плодов сироп със захар" (77, 93). **These are variants, not
   separate products** — see the data model below.
5. **Syrups are categorised by dilution ratio, not flavour.** A customer cannot browse "арония"
   across formats. Restructure to flavour categories with size/dilution as facets.
6. **Category copy references three products that do not exist** — Касис и Арония,
   Къпина и арония Light, and a Малина и арония naming mismatch.
7. **Homepage has zero prose.** All brand storytelling is buried in За нас and category descriptions.
8. **Image paths contain spaces and Cyrillic**, and a folder named `out of stock/` — filenames
   encode editorial state.

### Payments — greenfield

Probing OpenCart's payment controllers shows **only stock core files**: `cod`, `bank_transfer`,
`cheque`, `free_checkout` and the bundled international ones. **No Borica, myPOS, ePay.bg, EasyPay,
Paysera or Stripe.** No payment JS on the storefront, no card logos in the footer.

**They accept no card payments today.** COD (наложен платеж) is almost certainly the live method.
Guest checkout is enabled and confirmed working.

### Shipping

Bulgaria only — *"Доставки до адреси и офиси в чужбина не се извършват."* Couriers **Спиди** and
**Еконт**. Cost is not quoted; customers are sent to the couriers' own tariff pages. **Free shipping
over €80 (156.47 лв)**, to courier offices only.

> ⚠️ €80 on products costing €2.70–3.00 is ~27 bottles. Worth challenging.

### Legal gaps

🚨 **The Общи условия page is empty** — its body is the title repeated:

```html
<div id="content" class="col-sm-12">
  <h1>Общи условия за ползване</h1>
  <p>Общи условия за ползване</p>
</div>
```

For a B2C shop this misses the ЗЗП / EU Consumer Rights Directive requirements — 14-day withdrawal,
complaints procedure, trader identity. **Must be written from zero.** The returns link exists only
inside a `display:none` div. No email is published. No cookie consent banner. `/shop/admin/` is on
the default path and publicly reachable.

The privacy policy, by contrast, is substantive and names ЕИК 115796742, Еконт and Спиди.

### Domain

`vitanea.com` 301s to `/shop/`, but a **dead Joomla site is still live** at `/bg/` and `/en/` —
carrying the good science content (aronia botany, ORAC tables) with zero links from the shop. The
За нас page links to `http://vitanea.com/` as "фирмения сайт", which redirects back to the shop: a
loop. The root `robots.txt` is still Joomla's. `http://` is served without redirecting to HTTPS.

---

## 🚨 Health claims — read before writing any copy

**There are no authorised health claims for aronia, anthocyanins, or fruit polyphenols** under
Reg. (EC) 1924/2006. Of 230 antioxidant claims submitted in 2008, 8 received a positive opinion.
"Antioxidant" is not itself a permitted nutrition claim.

**Must not appear on the new site** — all currently live on Vitanea pages:
понижават кръвното налягане · therapeutic effects при атеросклероза, лъчеви увреждания, ракови
заболявания, интоксикация с тежки метали · при базедова болест · „силен антиоксидантен и **лечебен**
ефект" · извежда радиоактивни вещества · unqualified „естествен източник на антиоксиданти" ·
the invented "3,000–5,000 ORAC units per day" framing · „подходящ за диабетици" (regulated wording,
needs legal review).

Two evidence problems compound this: the **USDA withdrew its ORAC database in 2012**, stating the
values have no proven relevance to human health — yet ORAC figures lead most Vitanea product
descriptions. And the 2019 and 2025 meta-analyses of RCTs both found **no significant effect on
blood pressure**.

**Safe and stronger:**
1. Piggy-back authorised **vitamin C** claims where composition is lab-verified per SKU
   (≥7.5% NRV/100 ml for beverages).
2. Provable **nutrition claims** — без добавена захар, източник на фибри.
3. **Neutral composition facts** — "342 aronia berries per bottle", "not from concentrate",
   "cold-pressed", sorbitol as aronia's main natural sugar.
4. **Provenance and process** — Krachanov, the patents, УХТ Пловдив, BAS, the factory, zero-waste,
   the family. Fully legal, and far more differentiating than any health claim.

Science lives as **cited editorial on the Науката page**, never as product-page claims.
**Needs the client's Bulgarian food-law review before launch.**

---

## Information architecture

| Route | Page | Purpose |
|---|---|---|
| `/` | `shop/Home` | The composition table above |
| `/продукти` | `shop/Products` | Catalog, filter by flavour/series/format, sort |
| `/продукти/{slug}` | `shop/Product` | Gallery, composition panel, variants, nutrition, related |
| `/серии` | `shop/Series` | Product lines as editorial collections |
| `/защо-арония` | `shop/Aronia` | The fruit as subject |
| `/науката` | `shop/Science` | Krachanov, research timeline, cited studies |
| `/за-нас` | `shop/About` | Family, factory, zero-waste production |
| `/количка` · `/поръчка` · `/поръчка/{number}` | Cart · Checkout · Confirmation | |
| `/доставка` · `/общи-условия` · `/поверителност` · `/контакти` | `shop/Page` | Legal + contact |

---

## Data model

Money as **integer minor units**, never floats.

- **`categories`** — restructured **by flavour**, not dilution ratio
- **`series`** — product lines (Детска серия, Light, …) for the Серии nav item
- **`products`** — name, slug, description, series, SEO meta
- **`product_variants`** — **the key change.** size/volume, dilution ratio, `sku`, `price_minor`,
  `stock`, `is_active`. Resolves the four-products-named-"Арония плодов сироп" problem and makes
  dilution a facet rather than a category
- **`product_ingredients`** — name, percentage, **`is_added`** → the composition panel
- **`product_nutrition`** — per-100ml values, fruit content %, kcal/kJ
- **`product_images`** — path, alt, position (catalog has one each today; schema allows galleries)
- **`carts` / `cart_items`** · **`orders` / `order_items`** — order items snapshot name and price at
  purchase so later catalog edits never rewrite history

Enums in `app/Enums`: `OrderStatus`, `PaymentMethod`, `DeliveryMethod`, TitleCase keys.
Seeders carry the 27 real products — **product 96 excluded**, demo attributes dropped.

---

## Implementation phases

**1 — Foundation.** Retheme `resources/css/app.css`; fonts with Cyrillic subsets in
`vite.config.ts`; remove the stray `rsms.me/inter` link in `pages/Welcome.vue`; `APP_LOCALE=bg`;
`resources/lang/bg`; Bulgarian money and date formatters with EUR/BGN dual display.

**2 — Storefront shell.** `layouts/StoreLayout.vue` with header and footer. Register it in
`resources/js/app.ts`'s layout switch under a `shop/` branch — **without this, storefront pages
silently inherit the authenticated sidebar layout.** Cart count as a deferred prop through
`HandleInertiaRequests`, declared in `types/global.d.ts`.

**3 — Catalog.** Migrations, models, enums, factories, seeders; download imagery to
`storage/app/public` handling the space-and-Cyrillic paths; `storage:link`; controllers;
Products and Product pages with the composition panel and variant selector.

**4 — Brand pages.** Home per the composition table, Series, Защо арония?, Науката, За нас.
These carry the identity and deserve the most design attention.

**5 — Cart & checkout.** Cart service, FormRequest-validated checkout, order creation,
confirmation email and page. **COD + bank transfer**; `PaymentMethod` shaped so a gateway drops in.

**6 — Polish.** Legal pages (Общи условия **written from zero**), SEO meta, `Product` JSON-LD,
sitemap, responsive pass, empty and loading states, accessibility.

### Reuse — no new dependencies

Existing shadcn-vue: button, card, badge, dialog, sheet, select, input, label, checkbox, separator,
skeleton, sonner, navigation-menu, breadcrumb, tooltip, avatar, collapsible.
Add via `npx shadcn-vue@latest add`: **table, tabs, accordion, radio-group, textarea, pagination**.
Reuse `lib/utils.ts` `cn()`, `lib/flashToast.ts`, `useAppearance`, `useCurrentUrl`, the `Form` +
Wayfinder `formVariants` pattern, and the FormRequest + `Inertia::flash('toast')` convention in
`app/Http/Controllers/Settings/`.

### Conventions

`<script setup lang="ts">`; `type Props` + `withDefaults`; layout props via `defineOptions`;
`<Head>` per page; hrefs always Wayfinder calls, never string literals. Backend: FormRequests,
Laravel 13 `#[Fillable]`/`#[Hidden]` as on `User`, explicit return types, curly braces always.
ESLint enforces `import/order` and blank lines around control structures.

### Docs

`docs/README.md` and `docs/IMPLEMENTATION-PLAN.md` exist; add **`docs/COMPANY-CONTEXT.md`**
carrying the company profile, Krachanov material, catalog inventory and health-claim rules.

---

## Decisions

| # | Decision | Taken |
|---|---|---|
| 1 | Visual direction | **v1 palette site-wide; v2 supplies section structure.** v2's hero unused; discount and countdown devices dropped |
| 2 | Homepage sections | Category cards + benefit grid · Продукт във фокус · gallery + newsletter + trust row · science block replacing reviews |
| 3 | Scope | Phases 1–6, **COD + bank transfer**. Card gateway is its own milestone — note it is greenfield, nothing exists to migrate |
| 4 | Locale | **Bulgarian only** for now — ⚠️ but ~30% of revenue is export and the shop is BG-only. Worth revisiting; it is the one structural decision here |
| 5 | Currency | **EUR primary, BGN alongside** — matches what they already do |
| 6 | Product management | Seeders now, schema admin-ready |
| 7 | Database | SQLite for dev; MySQL/Postgres before production |

**Blocked on the client:** Krachanov's living status · which address is official · VAT number ·
food-law review of all benefit copy · current retail listings · organic/BIO certification status
(a reseller advertises a "БИО" Vitanea juice, but no certificate is named anywhere).

---

## Verification

- `php artisan test --compact` — catalog listing and filtering, product page renders with
  composition data, variant selection changes price and stock, cart add/update/remove, checkout
  creates an order with correct totals, confirmation mail queued. Factories for all data;
  `route()` names never hardcoded URLs.
- `composer ci:check` — Pint, PHPStan level 7, ESLint, Prettier, `vue-tsc`.
- `npm run build`, `composer run dev`, walk the full purchase path in the browser.
- Responsive at 375 / 768 / 1280 / 1536.
- **Cyrillic rendering check** — confirm Nunito and IBM Plex Mono serve real Cyrillic glyphs rather
  than a system fallback.
- Lighthouse on `/` and a product page; validate `Product` JSON-LD.
