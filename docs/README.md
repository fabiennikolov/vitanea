# Vitanea — Online Store

Rebuild of the Vitanea webshop, replacing the OpenCart install at `vitanea.com/shop/`.

| | |
|---|---|
| Stack | Laravel 13 · Inertia 3 · Vue 3.5 · Tailwind CSS v4 · shadcn-vue |
| Locale | Bulgarian (`bg`) |
| Currency | EUR, with BGN shown alongside |
| Docs | [Implementation plan](./IMPLEMENTATION-PLAN.md) · [Component plan](./COMPONENT-PLAN.md) · [Company & content context](./COMPANY-CONTEXT.md) |

---

## 1. The brief

Vitanea produces fruit-based functional drinks — aronia nectars, syrups and juices. The client's
brief is unusually specific about what this project is *not*:

> Не искаме просто нов сайт/магазин за сиропи/сокове, а да отразява тази идентичност.
> *We don't want just a new site/shop for syrups and juices — we want one that reflects this identity.*

Four pillars come out of the client's text. Every design and data decision in this project traces
back to one of them.

### 1.1 Category ownership

> единственият производител на бг пазара на функционални напитки
> *the only producer of functional drinks on the Bulgarian market*

Vitanea is not competing on flavour or price. The site should read as the category's origin, not
as one option among many.

### 1.2 Nothing added — the core differentiator

> Има подобни, но те са с нещо добавено — витамини, минерали и т.н. Функционалността на нашите
> продукти идва от полезността на самия плод.
> *There are similar products, but they have something added — vitamins, minerals and so on. The
> functionality of our products comes from the usefulness of the fruit itself.*

This is the sharpest claim in the brief and the hardest to express in a webshop, **because it is a
claim about an absence.** A conventional store has no vocabulary for "we did not add anything" —
its entire visual grammar is built around addition: more features, more discount, more urgency.

Our answer is the [composition panel](#34-the-nothing-added-device), which turns the absence into
the most emphatic element on the page and models it as real data rather than a slogan.

### 1.3 Real fruit

> Ние произвеждаме всичко от истински плодове.
> *We produce everything from real fruit.*

Production is part of the product. This earns the За нас page real content rather than boilerplate.

### 1.4 Scientific lineage

> аронията става известна в България, благодарение на научните изследвания на проф. Христо Крачанов
> *aronia became known in Bulgaria thanks to the scientific research of Prof. Hristo Krachanov*

And aronia's place in the range is stated, with a reason:

> Заради полезните ѝ качества аронията е плодът, който влагаме в почти всички наши продукти.
> *Because of its beneficial qualities, aronia is the fruit we put in almost all our products.*

Two nav items come straight out of these two sentences. **Науката** is a primary item rather than a
footer link because aronia's reputation in Bulgaria has a named origin — the strongest available
proof that "functional" here means something different than it does on a fortified competitor. And
**Защо арония?** earns its place because the fruit is the through-line of the whole range, not one
flavour among several.

---

## 2. Design mockups

Two mockups sit in `public/images/`, and they do **different jobs**.

| Mockup | Job |
|---|---|
| `hero_section_design_mock.jpg` | **The hero, and the visual language for the whole site** — palette, typography, restraint |
| `design_mock_v2.jpg` | **Layout and section structure for the pages** — what blocks exist and how they compose |

**v2 gives the shapes. v1 gives every colour.**

### 2.1 `hero_section_design_mock.jpg` — hero and visual language

A genuine Vitanea design: real bottles, real label artwork, written Bulgarian copy. Warm ivory
ground, deep plum ink, organic blob shapes, a high-contrast Cyrillic display serif.

- Eyebrow `ПРИРОДА × НАУКА` — nature and science as equal partners, which is exactly the brief
- Headline `Рестартирай отвътре.`
- Primary pill CTA `Открий Detox` beside a link-style secondary `Науката зад Vitanea`
- Nav: **Продукти · Серии · Защо арония? · Науката · За нас**
- Right rail: `VITANEA / DETOX 01` over spec pills — АРОНИЯ / РАСТИТЕЛНИ СЪСТАВКИ / ЕЖЕДНЕВЕН БАЛАНС

The navigation alone encodes all four pillars.

### 2.2 `design_mock_v2.jpg` — page layout

v2 is a template comp — it is branded "Botanica"/"GreenBoo", its bottles are Snapple and Fruit-Up
stock photos, and its copy is lorem ipsum. So it is used for **section structure only**, not for
its visual treatment, and **its hero is not used** — v1 wins there.

Two of its devices are dropped: the `50% DISCOUNT` ribbon and the "Deal Of The Day" countdown.
Discount ribbons and urgency timers signal commodity competition, which works directly against
pillar 1.

### 2.3 Homepage composition — v2's skeleton in v1's palette

| v2 section | Vitanea translation | Ground |
|---|---|---|
| Hero | v1 hero composition, run full-bleed | plum `#3A233B` |
| "Our Drinks Menu" — 3 cards | Сокове · Нектари · Сиропи, name inside the tile | rotating cream / sage |
| 6-item benefit grid | The four pillars + production principles from За нас | sage `#E0DAC5` |
| "Deal Of The Day" | **Продукт във фокус** — same layout, composition panel replaces the countdown | cream + berry |
| 4.9 rating panel | **Науката зад аронията** — Krachanov and cited research | plum `#3A233B` |
| Gallery grid | **Производство** — the Plovdiv factory, harvest, fruit | cream |
| Newsletter | As designed | sage |
| Footer | As designed; payment badges only once a gateway exists | plum |

The rating panel is replaced rather than filled because **Vitanea has zero reviews site-wide** —
there is nothing to populate it with. The same slot, same layout, carries the science instead.

---

## 3. Design system

### 3.1 Palette

Sampled directly from the mockup. These replace the stock shadcn neutral greyscale, which carries
no brand colour at all.

| Token | Hex | Role |
|---|---|---|
| `--color-cream-50` | `#F7EDE4` | Page background |
| `--color-sage-200` | `#E0DAC5` | Secondary panels, alternating sections |
| `--color-plum-950` | `#1E0A25` | Headlines and body ink |
| `--color-plum-900` | `#281D2B` | Primary button fill |
| `--color-plum-800` | `#3A233B` | Decorative shapes, dark sections |
| `--color-berry-600` | `#C10024` | Sole accent — prices, focus rings |
| `--color-aronia-950` | `#490206` | Deep product tone, dark surfaces |

Berry is the only saturated hue in the system. Used once per view it reads as emphasis; used freely
it becomes the sale-sticker red of mock v2.

### 3.2 Typography — Cyrillic is a hard requirement

`laravel-vite-plugin` defaults to `subsets: ['latin']`, so **any font added without an explicit
`cyrillic` subset renders every Bulgarian character in a system fallback.** That single rule has
already disqualified several otherwise obvious choices: Instrument Sans and Instrument Serif ship
no Cyrillic glyphs at all, and neither do the rounded display faces — Fredoka, Baloo 2, Quicksand.
**Check the subset before proposing a font, not after.**

| Role | Font | Rationale |
|---|---|---|
| `--font-display` | Nunito, 700 | Rounded terminals carry the friendly register; heavy weights give headlines the chunk the editorial serif used to get from contrast |
| `--font-sans` | Nunito, 400–600 | Same family in both roles — one voice, and a drawn Cyrillic cut rather than a synthesised one |

One family in both roles is deliberate. A rounded display face paired with a neutral body font
reads as two brands; Nunito across the whole range keeps headline and body in the same voice, which
is what makes the storefront feel approachable rather than clinical.

Loaded through the existing pipeline — no new dependency, fonts stay self-hosted:

```ts
fonts: [
    bunny('Nunito', { weights: [400, 500, 600, 700, 800], subsets: ['latin', 'latin-ext', 'cyrillic'] }),
    bunny('IBM Plex Mono', { weights: [400, 500], subsets: ['latin', 'latin-ext', 'cyrillic'] }),
],
```

### 3.3 Type rules

`font-bold` on display headlines, `font-semibold` on card and panel titles. Nunito is a low-contrast
rounded face — at the 400/500 weights an editorial serif could carry, it reads thin and generic, so
the weight is doing the work the serif's stroke contrast used to do. No `leading-*` on headings.
`tracking-tight` above `text-xl`. `text-balance` on headings, `text-pretty` on paragraphs. Width
constrained per element with `max-w-[*ch]`, never on the heading-group wrapper. `tabular-nums` on
every price so digits don't shift.

### 3.4 The "nothing added" device

The differentiator is an absence, so it needs a recurring form. A **composition panel** runs on
every product page, condensed on cards:

```
СЪСТАВ                        Типична функционална напитка
─────────────────────         ─────────────────────
Сок от арония ......  100%    Плодова основа
                              + Витамин C (добавен)
Нищо друго.                   + Цинк (добавен)
                              + Консервант
```

Two decisions make this work:

1. **It is large.** It occupies the slot a discount ribbon would take on a conventional store. The
   short list is the proof, so hiding it in a spec tab wastes the strongest asset.
2. **It is data, not copy.** `product_ingredients.is_added` is a real column, so "nothing added"
   is queryable and cannot drift out of sync with the product.

### 3.5 Layout

A two-element pattern for every section — outer handles background and vertical padding, inner
handles `max-w-* mx-auto` and horizontal padding — so content edges align down the whole page. One
shared column gap, one radius per container level, one primary and one link-style secondary button
reused everywhere.

The radius scale, roundest first: `rounded-[2.5rem]` on hero and product-page media, `rounded-[2rem]`
on catalog and category tiles, `rounded-[1.75rem]` on boxed panels, `rounded-2xl` on form controls
and selectable rows, `rounded-full` on every button and chip. Product imagery is sized
`object-contain` inside its tile with a percentage inset — constraining a single axis instead crops
whichever product photo has the opposite aspect.

### 3.6 Tiles are filled colour blocks

Catalog and category tiles are flat grounds that rotate `cream-200 → sage-200 → plum-800 →
cream-300` by grid position, with the product `object-bottom` so it stands on the tile's edge. Three
decisions hold this together:

1. **No blob inside a grid tile.** The blob behind a small centred product is exactly what made the
   catalog read as goods floating in empty boxes. The blob stays where it has room to be a shape —
   the hero and the product page.
2. **The product must be trimmed.** Transparent margin in the source file becomes dead space in the
   tile; see the rule in `.ai/rules/products.md`.
3. **Category tiles carry their name inside the block**, so a row of them reads as filled panels.
   This is why their grounds stay light — the name is plum ink and would fail on `plum-800`.

The hero follows the same logic at page scale: a full-bleed plum band carrying the headline, with
the bottle standing on the band's bottom edge, rather than a decorative shape inside the gutter. Left-aligned heading groups by default; centred reserved for the hero and the
closing CTA.

---

## 4. Current site audit

Full inventory — every product, image path, price and source — is in
**[COMPANY-CONTEXT.md](./COMPANY-CONTEXT.md)**. Summary:

`vitanea.com/shop/` runs **OpenCart 3.0.x**, Bulgarian only, **28 products** (27 real + 1 test).
EUR is already the primary currency with BGN shown parenthetically at 1.95583.

| Category | Products |
|---|---|
| Сокове | 1 |
| Нектари | 6 — Без захар 1л, Със захар 1л, Детска серия |
| Сиропи | 16, split across six **dilution-ratio** subcategories |
| Пектини | 2 |
| Чайове | 2 (+1 test) |
| Промоции | empty |

Prices €1.44–€9.45. **14 of 28 are out of stock**, including the flagship Сок от арония 100%, both
teas, both pectins and the entire children's line — with no back-in-stock capture.

### What has to be fixed in the rebuild

1. 🚨 **A live test product is publicly purchasable** — `product_id=96`, "тестови продукт", €10.00,
   in stock, described "For test purposes of EUR functionality". Worth flagging to the client as an
   urgent fix on the *current* site too.
2. **Demo data leftovers** — the Нектари category description is still
   `"Example of category description text"`; product 52's spec tab contains
   `Processor / Clockspeed: 100mhz`.
3. **No real SKUs.** The `model` field is relabelled „Вид продукт" and holds a format string.
4. **Duplicate names across sizes** — four products named "Арония плодов сироп със захар"
   (ids 72, 86, 88), two named "Ягода плодов сироп със захар" (77, 93). **These are variants**,
   which is why the data model in §7 introduces `product_variants`.
5. **Syrups are categorised by dilution ratio, not flavour** — a customer cannot browse "арония"
   across formats.
6. **Category copy references three products that don't exist.**
7. **Homepage has zero prose.** All brand storytelling is buried in За нас and category descriptions.
8. **Image paths contain spaces and Cyrillic**, plus a folder literally named `out of stock/`.

### Payments — greenfield

Probing OpenCart's payment controllers returns **only stock core files**. No Borica, myPOS,
ePay.bg, EasyPay, Paysera or Stripe. No payment JS, no card logos. **Vitanea accepts no card
payments today** — COD is the realistic live method. Card payment on the new site is a build from
scratch, not a migration.

### Shipping

Bulgaria only, via **Спиди** and **Еконт**. Cost is not quoted — customers are linked out to the
couriers' own tariff pages. **Free shipping over €80**, to courier offices only.

> ⚠️ €80 on products costing €2.70–3.00 is ~27 bottles. Worth challenging.

### Legal

🚨 **The Общи условия page is empty** — its body is the title repeated. For a B2C shop that misses
ЗЗП / EU Consumer Rights Directive requirements. **Must be written from zero.** Also: the returns
link is inside a `display:none` div, no email is published anywhere, there's no cookie banner,
`/shop/admin/` is on the default path and publicly reachable, and `http://` is served without
redirecting to HTTPS.

The privacy policy is substantive by contrast, and names **ВИТАНЕА ООД, ЕИК 115796742**.

### Domain

`vitanea.com` 301s to `/shop/`, but a **dead Joomla site is still live** at `/bg/` and `/en/`,
holding the good science content with zero links from the shop. За нас links to the root as
"фирмения сайт", which redirects back to the shop — a loop. The root `robots.txt` is still Joomla's.

---

## 5. Health claims — read before writing copy

**There are no authorised health claims for aronia, anthocyanins or fruit polyphenols** under EU
Regulation 1924/2006. Of 230 antioxidant claims submitted in 2008, 8 received a positive opinion.
"Antioxidant" is not itself a permitted nutrition claim, and disease claims are absolutely
prohibited.

Vitanea's current pages carry a number of claims that cannot move to the new site — including that
aronia lowers blood pressure, and therapeutic effects for atherosclerosis, radiation damage, cancer,
heavy-metal intoxication and Graves' disease. The full ❌ list is in
[COMPANY-CONTEXT.md §7](./COMPANY-CONTEXT.md).

Two evidence problems compound this:

- **The USDA withdrew its ORAC database in 2012**, stating the values have no proven relevance to
  human health — yet ORAC figures lead most Vitanea product descriptions.
- **Blood-pressure claims aren't supported.** The 2019 and 2025 meta-analyses of RCTs both found no
  significant effect.

The compliant pattern is also the better one for this brief: present the science as **cited research
on the Науката page**, attributed to Krachanov and named studies, rather than as claims attached to
a product. Product-level claims would merely make Vitanea look like the fortified competitors it is
distinguishing itself from — provenance and process are both legal and more differentiating.

Nutrient claims such as *„източник на витамин C"* are permitted where composition is lab-verified per
SKU (≥7.5% NRV per 100 ml for beverages).

**This needs a Bulgarian food-law review before launch.** It is the single item most likely to force
late copy rewrites across every product page.

---

## 6. Information architecture

Bulgarian-slugged routes; navigation mirrors the mockup.

| Route | Page | Purpose |
|---|---|---|
| `/` | `shop/Home` | Hero, series, aronia teaser, Krachanov teaser, trust row |
| `/продукти` | `shop/Products` | Catalog with filter and sort |
| `/продукти/{slug}` | `shop/Product` | Gallery, composition panel, nutrition, related |
| `/серии` | `shop/Series` | Product lines as editorial collections |
| `/защо-арония` | `shop/Aronia` | The fruit as subject — origin, composition, cultivation |
| `/науката` | `shop/Science` | Krachanov, research timeline, cited studies |
| `/за-нас` | `shop/About` | Producer story, real-fruit production |
| `/количка` | `shop/Cart` | Cart |
| `/поръчка` | `shop/Checkout` | Checkout |
| `/поръчка/{number}` | `shop/OrderConfirmation` | Thank-you and order summary |
| `/доставка` · `/общи-условия` · `/поверителност` · `/контакти` | `shop/Page` | Legal and contact |

---

## 7. Data model

Money is stored as **integer minor units** throughout — never floats.

| Table | Notes |
|---|---|
| `categories` | Restructured **by flavour**, not by dilution ratio |
| `series` | Product lines (Детска серия, Light…) — the Серии nav item |
| `products` | name, slug, description, series, SEO meta |
| `product_variants` | **The key change** — size/volume, dilution ratio, `sku`, `price_minor`, `stock`, `is_active` |
| `product_ingredients` | name, percentage, **is_added** — powers the composition panel |
| `product_nutrition` | per-100ml values, fruit content %, kcal/kJ |
| `product_images` | path, alt, position |
| `carts` · `cart_items` | session- and user-bound |
| `orders` · `order_items` | number, status, customer and delivery fields, payment method, totals, paid_at |

`product_variants` is what resolves the four-products-all-named-"Арония плодов сироп със захар"
problem found in the audit, and turns dilution ratio into a facet rather than a category.

Order items snapshot name and price at purchase time, so later catalog edits never rewrite history.
Enums live in `app/Enums` — `OrderStatus`, `PaymentMethod`, `DeliveryMethod`.

---

## 8. Getting started

```bash
composer setup        # install, .env, key:generate, migrate, npm install, npm run build
composer run dev      # serve + vite + queue + logs
```

| Command | Purpose |
|---|---|
| `php artisan test --compact` | Run the test suite |
| `composer ci:check` | Pint, PHPStan level 7, ESLint, Prettier, vue-tsc |
| `vendor/bin/pint --dirty` | Format changed PHP |
| `npm run lint` / `npm run format` | Lint and format the frontend |
| `php artisan wayfinder:generate` | Regenerate typed route helpers |

### Two traps in this codebase

1. **Layouts are assigned by page-name prefix** in `resources/js/app.ts`. Storefront pages under
   `pages/shop/*` need a `case name.startsWith('shop/')` branch, or they silently inherit the
   authenticated sidebar layout.
2. **Fonts default to Latin-only subsets** (§3.2). Any font added without an explicit
   `subsets: ['latin', 'cyrillic']` will render Bulgarian in a system fallback.

---

## 9. Conventions

Frontend: `<script setup lang="ts">` everywhere, `type Props` with `withDefaults`, layout props via
`defineOptions({ layout: {…} })`, `<Head>` per page, and hrefs always as Wayfinder function calls —
never string literals.

Backend: FormRequests rather than inline validation, Laravel 13 `#[Fillable]` / `#[Hidden]`
attributes as on `User`, explicit return types on every method, curly braces always.

ESLint enforces `import/order` and blank lines around control structures. Both are auto-fixable
with `npm run lint`.
