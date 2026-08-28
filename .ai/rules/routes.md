---
paths:
  - 'resources/js/routes/**'
---

# Routes

## Regenerate Wayfinder routes with --with-form
Always run `php artisan wayfinder:generate --with-form`.

vite.config.ts configures the plugin with `formVariants: true`, so pages call helpers like `store.form()` and `update.form()`. Running the artisan command without the flag regenerates the helpers without those variants and breaks `vue-tsc` in every page that uses `.form()` — Checkout, Profile, Security and VerifyEmail among them — even though none of those files were touched.

`npm run build` regenerates via the Vite plugin and is safe.
