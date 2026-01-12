import type { Metadata } from "next";
import "./globals.css";
import Script from "next/script";
import BodyClass from "@/components/BodyClass";
import SwiperInit from "@/components/SwiperInit";
import ConditionalLayout from "@/components/ConditionalLayout";

export const metadata: Metadata = {
  metadataBase: new URL('https://www.naaelahsaleh.co'),
  title: {
    default: "Naaelah Saleh & Co | Law Firm Malaysia",
    template: "%s | Naaelah Saleh & Co",
  },
  description:
    "Naaelah Saleh & Co is a trusted law firm in Malaysia providing comprehensive legal services in Civil Litigation, Criminal Law, Conveyancing, and Grant of Probate.",
  keywords: [
    "Naaelah Saleh & Co",
    "Law Firm Malaysia",
    "Lawyer Malaysia",
    "Civil Litigation",
    "Criminal Law",
    "Conveyancing",
    "Grant of Probate",
    "Legal Services Malaysia",
    "Sungai Pelek Lawyer",
    "Sendayan Lawyer",
    "Selangor Law Firm",
    "Negeri Sembilan Lawyer",
  ],
  alternates: { canonical: "https://www.naaelahsaleh.co" },
  openGraph: {
    type: "website",
    url: "https://www.naaelahsaleh.co",
    title: "Naaelah Saleh & Co | Law Firm Malaysia",
    description:
      "Trusted law firm providing Civil Litigation, Criminal Law, Conveyancing, and Grant of Probate services in Malaysia.",
    siteName: "Naaelah Saleh & Co",
  },
  twitter: {
    card: "summary_large_image",
    title: "Naaelah Saleh & Co | Law Firm Malaysia",
    description:
      "Trusted law firm providing comprehensive legal services in Malaysia.",
  },
  robots: {
    index: true,
    follow: true,
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <head>
        {/* Schema.org JSON-LD */}
        <Script
          id="ld-org"
          type="application/ld+json"
          strategy="afterInteractive"
          dangerouslySetInnerHTML={{
            __html: JSON.stringify({
              '@context': 'https://schema.org',
              '@type': 'LegalService',
              name: 'Naaelah Saleh & Co',
              url: 'https://www.naaelahsaleh.co',
              email: 'enquiry@naaelahsaleh.co',
              contactPoint: [
                {
                  '@type': 'ContactPoint',
                  contactType: 'customer support',
                  email: 'enquiry@naaelahsaleh.co',
                  areaServed: 'MY',
                  availableLanguage: ['en', 'ms'],
                },
              ],
              address: {
                '@type': 'PostalAddress',
                streetAddress: 'No.6, Jalan Tasik Beringin 1, Taman Sepang Putra',
                addressLocality: 'Sungai Pelek',
                addressRegion: 'Selangor',
                postalCode: '43950',
                addressCountry: 'MY',
              },
              telephone: '+60193186436',
            }),
          }}
        />
        <Script
          id="ld-website"
          type="application/ld+json"
          strategy="afterInteractive"
          dangerouslySetInnerHTML={{
            __html: JSON.stringify({
              '@context': 'https://schema.org',
              '@type': 'WebSite',
              name: 'Naaelah Saleh & Co',
              url: 'https://www.naaelahsaleh.co',
              publisher: {
                '@type': 'Organization',
                name: 'Naaelah Saleh & Co',
              },
            }),
          }}
        />

        {/* Fonts - exactly same as kf-next */}
        <link href="https://fonts.googleapis.com" rel="preconnect" />
        <link href="https://fonts.gstatic.com" rel="preconnect" crossOrigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet" />

        {/* Vendor CSS */}
        <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
        <link href="/assets/vendor/aos/aos.css" rel="stylesheet" />
        <link href="/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet" />
        <link href="/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

        {/* Main CSS */}
        <link href="/assets/css/main.css" rel="stylesheet" />
      </head>
      <body>
        <BodyClass />
        <SwiperInit />
        <ConditionalLayout>
          {children}
        </ConditionalLayout>

        {/* Vendor JS */}
        <Script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js" strategy="afterInteractive" />
        <Script src="/assets/vendor/aos/aos.js" strategy="beforeInteractive" />
        <Script id="init-aos" strategy="afterInteractive" dangerouslySetInnerHTML={{
          __html: "window.AOS&&AOS.init({duration:600,easing:'ease-in-out',once:true,mirror:false});"
        }} />
        <Script src="/assets/vendor/glightbox/js/glightbox.min.js" strategy="afterInteractive" />
        <Script src="/assets/vendor/swiper/swiper-bundle.min.js" strategy="afterInteractive" />
        <Script src="/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js" strategy="afterInteractive" />
        <Script src="/assets/vendor/isotope-layout/isotope.pkgd.min.js" strategy="afterInteractive" />
        <Script src="/assets/vendor/waypoints/noframework.waypoints.js" strategy="afterInteractive" />
        <Script src="/assets/vendor/purecounter/purecounter_vanilla.js" strategy="afterInteractive" />
        <Script src="/assets/js/main.js" strategy="afterInteractive" />
      </body>
    </html>
  );
}
