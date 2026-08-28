# Component Execution Plan

Component-level breakdown of the work in [IMPLEMENTATION-PLAN.md](./IMPLEMENTATION-PLAN.md).
Phases are the *order*; this file is the *inventory*.

**Status legend:** ✅ built · 🟡 partial · ⬜ not started

---

## Where we are

**All six phases are complete.** 78 tests pass; Pint, PHPStan level 7, ESLint, Prettier and
`vue-tsc` are clean.

| Phase | Status |
|---|---|
| 1 — Foundation | ✅ Tokens, Cyrillic fonts, `Money`, `bg` locale |
| 2 — Shell & primitives | ✅ `StoreLayout`, header, footer, 5 shared primitives, 12 routes |
| 3 — Catalog | ✅ 8 tables, 22 products / 27 variants seeded from the real shop |
| 4 — Brand pages | ✅ Homepage (8 sections), Науката, Защо арония?, За нас, Серии |
| 5 — Cart & checkout | ✅ Session cart, orders, COD + bank transfer, confirmation email |
| 6 — Polish | ✅ Legal pages written, JSON-LD, sitemap, robots.txt |

### Built

**Backend** — `Category`, `Series`, `Fruit`, `Product`, `ProductVariant`, `ProductIngredient`,
`ProductNutrition`, `ProductImage`, `Order`, `OrderItem`; `OrderStatus` / `PaymentMethod` /
`DeliveryMethod` enums; `Money`, `Cart`, `CartLine`; Home / Product / Series / Cart / Checkout /
Page / Sitemap controllers; `CatalogSeeder` with the real catalog; factories for every model.

**Frontend** — `SectionShell`, `SectionHeading`, `ActionButton`, `SpecPill`, `BlobField`;
`ProductCard`, `PriceDisplay`, `StockBadge`, `CompositionPanel`, `VariantSelector`,
`NutritionTable`; `CategoryCards`, `BenefitGrid`, `ProductInFocus`, `ScienceBlock`,
`ProductionGallery` (as `TrustRow` + featured grid), `NewsletterForm`, `TrustRow`.

### Deliberately not built

| | Why |
|---|---|
| Card payment gateway | Nothing exists to migrate — the current shop has no gateway installed at all. `PaymentMethod` is shaped so myPOS or Borica drops in |
| Admin panel | Seeders cover the catalog today; schema is admin-ready |
| Product galleries | Every product on the current site has exactly one image. The schema supports many; the photography does not exist yet |
| `BackInStockForm` | Needs a decision on where subscriptions go before it is worth building |
| Search | The header control is present but inert |

---

## Phase 2 — Shell and shared primitives ✅

| Component | Status |
|---|---|
| `layouts/StoreLayout.vue` | ✅ Registered in `resources/js/app.ts` under `name.startsWith('shop/')` |
| `components/store/StoreFooter.vue` | ✅ Four link columns, ЕИК 115796742, real address, phone, email, socials |
| `components/store/SectionShell.vue` | ✅ `ground`: `cream` \| `sage` \| `plum` |
| `components/store/SectionHeading.vue` | ✅ Per-element `max-w-[*ch]`, `tone` for dark grounds |
| `components/store/ActionButton.vue` | ✅ `primary` \| `link`, both with a light tone for plum sections |
| `components/store/SpecPill.vue` | ✅ Optional icon, asymmetric padding per the badge rule |
| `components/store/BlobField.vue` | ✅ Plus `blob-a` / `blob-b` / `blob-c` `@utility` radii in `app.css` |
| Cart count shared prop | ✅ `HandleInertiaRequests::cartCount()`, typed in `global.d.ts`, covered by tests |
| Routes | ✅ `routes/shop.php` — 12 routes, Cyrillic slugs verified working |

---

## Phase 3 — Catalog

Backend first (migrations, models, enums, factories, seeders from the 27 real products), then:

| Component | Notes |
|---|---|
| `PriceDisplay.vue` | EUR primary, BGN beneath. `tabular-nums`. Wraps the `Money` output |
| `StockBadge.vue` | В наличност / Разпродаден. **14 of 28 products are out of stock**, so this is prominent, not incidental |
| `ProductCard.vue` | Image, name, variant hint, price, stock. Condensed composition line |
| `ProductGrid.vue` | Responsive grid; shares one `gap-*` with every other multi-column section |
| `CatalogFilters.vue` | By flavour, series, format. **Not by dilution ratio** — that was the old site's mistake |
| `CatalogSort.vue` · `CatalogPagination.vue` | Needs shadcn `select` (present) and `pagination` (to add) |
| `ProductGallery.vue` | Schema supports many images; the scraped catalog has exactly one each, so build the single-image case well and let it grow |
| `VariantSelector.vue` | Size / dilution. This is what collapses the four products all named "Арония плодов сироп със захар" into one |
| **`CompositionPanel.vue`** | **The centrepiece.** Vitanea's ingredients beside a typical fortified competitor, driven by `product_ingredients.is_added`. Takes the slot a discount ribbon would occupy |
| `NutritionTable.vue` | Per 100 ml, fruit content %, kcal/kJ. Needs shadcn `table` |
| `QuantityStepper.vue` · `AddToCartForm.vue` | Inertia `<Form>` + Wayfinder `formVariants` |
| `RelatedProducts.vue` | Same series or same fruit |
| `BackInStockForm.vue` | Email capture on sold-out products — the current site has nothing, and half the catalog is out of stock |

---

## Phase 4 — Homepage sections and brand pages

Each maps to a block in mock v2's skeleton, rendered in v1's palette.

| Section | v2 origin | Ground | Notes |
|---|---|---|---|
| ✅ `HeroSection` | — (mock v1) | cream | Done |
| `CategoryCards.vue` | "Our Drinks Menu" | cream | Сокове · Нектари · Сиропи, product on a plum blob |
| `BenefitGrid.vue` | 6-item benefit grid | sage | Four pillars + production principles from За нас |
| `ProductInFocus.vue` | "Deal Of The Day" | cream + berry | Same layout, **countdown and discount removed**; reuses `CompositionPanel` |
| `ScienceBlock.vue` | 4.9 rating panel | plum | Krachanov. Replaces reviews because Vitanea has **zero reviews site-wide** |
| `ProductionGallery.vue` | gallery grid | cream | Factory, harvest, fruit — ⚠️ needs photography we do not have yet |
| `NewsletterForm.vue` | subscribe | sage | |
| `TrustRow.vue` | trust row | — | Еконт/Спиди · sigurno plashtane · free over €80 |

**Brand pages**, all composed from the primitives above:

| Page | Distinct components |
|---|---|
| `Науката` | `ResearchTimeline` (1978 → 1984 → 1998), `CitationCard`, `StatRow` (150+ papers · 40 inventions · 9 patents) |
| `Защо арония?` | `FruitProfile`, `CoPigmentationExplainer` — *why* aronia sits in the strawberry and raspberry products, per Krachanov's published pigment-stability work |
| `За нас` | `FamilyStory`, `FactoryPanel`, `PrinciplesList` |
| `Серии` | Series as editorial collections |

---

## Phase 5 — Cart and checkout

| Component | Notes |
|---|---|
| `CartLineItem.vue` · `CartSummary.vue` · `EmptyCart.vue` | |
| `CheckoutForm.vue` | FormRequest-validated. Guest checkout — the current site allows it, don't regress |
| `DeliveryMethodPicker.vue` | Еконт / Спиди office selection. The old site sent people off-site to guess the price; quoting it here is a real improvement |
| `PaymentMethodPicker.vue` | **COD + bank transfer.** Card is greenfield — no gateway exists to migrate |
| `OrderSummary.vue` · `OrderConfirmation.vue` | Line items snapshot name and price at purchase |
| Confirmation mail | Bulgarian, needs `lang/bg` validation strings by this point |

---

## Phase 6 — Polish

Legal pages (**Общи условия written from zero** — the current one is empty), `ProseContent.vue` for
CMS text, SEO meta, `Product` JSON-LD, sitemap, empty and loading states, accessibility pass,
responsive sweep at 375 / 768 / 1280 / 1536.

---

## shadcn-vue components still to add

Present: alert, avatar, badge, breadcrumb, button, card, checkbox, collapsible, dialog,
dropdown-menu, input, input-otp, label, navigation-menu, select, separator, sheet, sidebar,
skeleton, sonner, spinner, tooltip.

To add with `npx shadcn-vue@latest add`: **table, tabs, accordion, radio-group, textarea,
pagination**. These are vendored source files, not new package dependencies.

---

## Known gaps

- **Product photography.** Every product on the current site has exactly one image, masters are
  1200×1200 (600×600 for the two newest), and there are no lifestyle or production shots at all.
  `ProductionGallery` and `ProductGallery` cannot be finished without a shoot.
- **Copy.** Only the hero is written. Every section below needs Bulgarian copy, and all benefit
  language must clear the health-claim rules in [COMPANY-CONTEXT.md §7](./COMPANY-CONTEXT.md).
- **Krachanov's living status** still blocks final tense on Науката and За нас.
