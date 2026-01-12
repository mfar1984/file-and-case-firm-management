"use client";

import { usePathname } from "next/navigation";
import Header from "./Header";
import Footer from "./Footer";
import Topbar from "./Topbar";
import WhatsAppFloat from "./WhatsAppFloat";

export default function ConditionalLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  
  // Pages that don't need header/footer (e.g., admin pages if any)
  const noLayout = pathname?.startsWith("/admin") || pathname?.startsWith("/auth");

  if (noLayout) {
    return <>{children}</>;
  }

  return (
    <>
      <Topbar />
      <Header />
      <main className="main">
        {children}
      </main>
      <Footer />
      <WhatsAppFloat />
      <a href="#" id="scroll-top" className="scroll-top d-flex align-items-center justify-content-center">
        <i className="bi bi-arrow-up-short"></i>
      </a>
    </>
  );
}

