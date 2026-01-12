# Naeelah Saleh & Co - Law Firm Website

Professional law firm website built with **Next.js 16**, **TypeScript**, and **Bootstrap 5** - exactly matching the kf-next template design and structure.

## 🎨 Design Features

**Exactly Same as kf-next:**
- ✅ **Fonts**: Open Sans, Jost, Poppins (Google Fonts)
- ✅ **Framework**: Next.js 16 with TypeScript + Turbopack
- ✅ **Styling**: Bootstrap 5 + Custom CSS (from kf-next template)
- ✅ **Animations**: AOS (Animate On Scroll)
- ✅ **Components**: Swiper, GLightbox, Isotope
- ✅ **Layout**: Topbar, Header, Footer structure sama seperti kf-next

## 📋 Menu Structure

1. **Home** - Landing page
2. **About** (Dropdown)
   - About Us
   - Our Services
3. **Practice** (Dropdown)
   - Civil Litigation
   - Criminal Law
   - Conveyancing
   - Grant of Probate
4. **Status Check** - Case status lookup
5. **Contact Us** - Contact form & info

## 🛠️ Technology Stack

- **Next.js 16** - React framework with App Router
- **TypeScript** - Type-safe development
- **Bootstrap 5** - UI framework (exactly like kf-next)
- **AOS** - Scroll animations
- **Swiper** - Carousels/sliders
- **GLightbox** - Lightbox gallery
- **Isotope** - Layout masonry

## 🚀 Getting Started

### Development Server
```bash
npm run dev
```
Visit: http://localhost:3000

### Production Build
```bash
npm run build
npm start
```

## 📂 Project Structure

```
naeelah-website/
├── public/
│   └── assets/           # COPIED from kf-next
│       ├── css/          # main.css (same as kf-next)
│       ├── js/           # main.js
│       ├── vendor/       # Bootstrap, AOS, Swiper, etc
│       └── img/          # Images
├── src/
│   ├── app/
│   │   ├── layout.tsx    # Root layout (same structure as kf-next)
│   │   ├── page.tsx      # Homepage
│   │   ├── about/
│   │   ├── practice/
│   │   ├── contact/
│   │   └── status-check/
│   └── components/
│       ├── Header.tsx    # Navigation header
│       ├── Footer.tsx    # Footer
│       ├── Topbar.tsx    # Top bar (contact info)
│       ├── Menu.tsx      # Navigation menu with dropdowns
│       ├── BodyClass.tsx # Client-side scroll/mobile handlers
│       ├── SwiperInit.tsx # Swiper initialization
│       └── ConditionalLayout.tsx # Layout wrapper
└── package.json
```

## 🎯 Features

### Exactly Same as kf-next:
1. **Topbar** - Slides down on scroll with contact info
2. **Fixed Header** - Sticky navigation with dropdown menus
3. **Hero Section** - Full-width hero with call-to-action
4. **Services Section** - 4 practice areas displayed as cards
5. **About Section** - Company information
6. **Stats Counter** - Animated statistics
7. **Call to Action** - CTA section
8. **Contact Form** - Full contact page with map
9. **Status Check** - Client case status lookup
10. **Footer** - Multi-column footer with links
11. **Scroll to Top** - Animated scroll button
12. **Mobile Navigation** - Responsive mobile menu
13. **AOS Animations** - Scroll-triggered animations

## 🎨 Design System

### Fonts (Same as kf-next)
- **Default**: Open Sans
- **Headings**: Jost
- **Navigation**: Poppins

### Colors (Adapted for Law Firm)
- **Primary**: #37517e (Professional Blue)
- **Accent**: #47b2e4 (Light Blue)
- **Dark**: #263449 (Deep Blue)
- **Background**: #ffffff / #f5f6f8

## 📱 Responsive Design

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

Fully responsive with mobile-first approach, exactly like kf-next.

## 🔧 Configuration

### Next.js Config
- App Router enabled
- TypeScript enabled
- Turbopack enabled
- Image optimization

### Environment Variables
Create `.env.local`:
```env
NEXT_PUBLIC_SITE_URL=https://www.naeelahsaleh.com
```

## 📄 Pages Created

✅ Homepage with Hero, Services, About, Stats
✅ About page (index)
✅ Contact page with form and map
✅ Practice areas page
✅ Status Check page with form and FAQ
⏳ Individual practice area pages (to be added)
⏳ About Us detailed page
⏳ Our Services detailed page

## 🚀 Deployment

### Build for Production
```bash
npm run build
```

### Deploy to:
- **Vercel** (Recommended for Next.js)
- **Netlify**
- **cPanel with Node.js support**

### Static Export (if needed)
Add to `next.config.ts`:
```typescript
output: 'export'
```

## 📝 Next Steps

1. **Add Logo**: Replace text logo with actual firm logo
2. **Add Images**: Add real images to `/public/assets/img/`
3. **Create Sub-pages**: Create detailed practice area pages
4. **Content**: Add real content for About Us, Services
5. **Backend Integration**: Connect contact form to email API
6. **Case Status**: Connect to actual database for case lookup
7. **SEO**: Add more metadata, sitemap, robots.txt

## 🔗 References

Based on **kf-next** template structure:
- Same fonts and typography
- Same layout components
- Same Bootstrap setup
- Same vendor libraries
- Same animation system

## 📞 Support

For questions or modifications, contact the development team.

---

**Built exactly like kf-next** ✨
**Tech Stack**: Next.js 16 + TypeScript + Bootstrap 5 + AOS
