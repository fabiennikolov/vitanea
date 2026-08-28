export type Ingredient = {
    name: string;
    percentage: number | null;
    isAdded: boolean;
};

export type Variant = {
    id: number;
    sku: string;
    label: string;
    dilution: string | null;
    packaging: string | null;
    priceMinor: number;
    price: string;
    priceBgn: string;
    stock: number;
    inStock: boolean;
};

export type Nutrition = {
    kcalPer100: number | null;
    kjPer100: number | null;
    fruitContentPercent: number | null;
};

export type FruitTag = {
    name: string;
    slug: string;
    isSignature: boolean;
};

export type Product = {
    id: number;
    name: string;
    slug: string;
    shortDescription: string | null;
    description: string | null;
    usage: string | null;
    storage: string | null;
    category: { name: string; slug: string };
    series?: { name: string; slug: string; tagline: string | null } | null;
    fruits?: FruitTag[];
    image: string | null;
    images?: { path: string; alt: string | null }[];
    variants: Variant[];
    ingredients?: Ingredient[];
    nutrition?: Nutrition | null;
    hasNothingAdded: boolean;
    inStock: boolean;
    fromPrice: string | null;
    fromPriceBgn: string | null;
    hasMultipleVariants: boolean;
};

export type CatalogFilters = {
    /** Free-text search over product name and short description. */
    q?: string | null;
    category?: string | null;
    /** Multi-select: several fruits OR together within the facet. */
    fruit?: string[] | null;
    series?: string | null;
    sort?: string | null;
    in_stock?: boolean | null;
};
