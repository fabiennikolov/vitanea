---
paths:
  - 'public/images/products/**'
---

# Products

## Product images are pre-trimmed; keep them that way
Every PNG in public/images/products is trimmed to its content bounds (`magick FILE -trim +repage FILE`). Untrimmed originals are kept in storage/app/product-images-original.

The catalog and category tiles render the product with `object-contain object-bottom` inside a flat colour block, so any transparent margin baked into the file shows up directly as dead space and the product stops standing on the tile edge. A new product photo dropped in untrimmed will look visibly smaller than its neighbours.

Note the out-of-stock badge in some files is authored bleeding off the top-right of the canvas — it looks clipped in the tile, and that is the asset, not a layout bug.
