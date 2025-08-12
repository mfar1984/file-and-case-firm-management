# Naeelah Firm UI Style Guide

## 1. Font Family
- **Primary:** `Figtree`, `Poppins`, or Tailwind default `font-sans`.
- **Imports:**
  ```html
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  ```

## 2. Font Size & Typography
| Element             | Tailwind Class                | Example Usage                                  |
|---------------------|------------------------------|------------------------------------------------|
| Heading Utama       | `text-lg md:text-xl font-bold text-gray-800` | `<h1 class="text-lg md:text-xl font-bold text-gray-800">...</h1>` |
| Section Heading     | `text-sm font-semibold text-gray-700`        | `<h3 class="text-sm font-semibold text-gray-700">...</h3>`        |
| Label               | `text-xs font-medium text-gray-700`          | `<label class="text-xs font-medium text-gray-700">...</label>`    |
| Input/Placeholder   | `text-xs`                                   | `<input class="text-xs ...">`                                     |
| Sidebar Brand       | `text-lg font-bold text-gray-800`            | `<span class="text-lg font-bold text-gray-800">...</span>`        |
| Sidebar Menu        | `text-xs`                                    | `<span class="text-xs">...</span>`                                |
| Help Text           | `text-xs text-gray-500`                      | `<p class="text-xs text-gray-500">...</p>`                        |

## 3. Text Color
- **Primary:** `text-gray-800` (heading utama, brand)
- **Section/Label:** `text-gray-700`
- **Secondary:** `text-gray-600` (button cancel, secondary text)
- **Placeholder/Help:** `text-gray-500`
- **Input:** `text-gray-700`
- **Button:**
  - Utama: `text-white` (on `bg-blue-600`)
  - Cancel: `text-gray-600` (on `bg-white` or `border-gray-300`)
- **No black color** is used anywhere.

## 4. Icon (Material Icons)
- **Import:**
  ```html
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  ```
- **Size:**
  - Sidebar: `text-xs`, `text-sm`, `text-base`
  - Heading: `text-lg`
  - Button: `text-xs`
- **Color:**
  - Sidebar: `text-blue-600`, `text-amber-700`, `text-gray-500`, `text-green-600`, `text-purple-600`, `text-orange-600`
  - Button: follows button color (white on blue, gray on cancel)
- **Example:**
  ```html
  <span class="material-icons text-blue-600 text-xs mr-2">business</span>
  <span class="material-icons text-lg text-purple-600">add_circle</span>
  ```

## 5. Input, Label, Placeholder, Help Text
- **Label:**
  - `block text-xs font-medium text-gray-700`
- **Input:**
  - `w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500`
- **Placeholder:**
  - `text-xs text-gray-500`
- **Help Text:**
  - `text-xs text-gray-500 mt-1`
- **Blade Components:**
  - `resources/views/components/input-label.blade.php`
  - `resources/views/components/text-input.blade.php`

## 6. Button
- **Primary:**
  - `bg-blue-600 text-white rounded-md text-xs font-medium hover:bg-blue-700`
- **Cancel:**
  - `text-gray-600 border border-gray-300 rounded-md text-xs font-medium hover:bg-gray-50`

## 7. Card/Section
- `bg-gray-50 p-4 rounded-lg mb-6`
- **Shadow:** `shadow-md` on main card

## 8. General Layout
- **Grid:** `grid-cols-1 md:grid-cols-2 gap-4` for forms/sections
- **Spacing:** Use `mb-6` for section separation

---

**Note:**
- All font sizes, colors, and icon sizes should follow the above for consistency.
- No black color should be used for text or icons.
- If you need to override or set a single font for the whole system, update the Tailwind config or add a global CSS rule. 