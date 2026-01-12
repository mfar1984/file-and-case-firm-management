"use client";

import Link from "next/link";
import Menu from "@/components/Menu";

export default function Header() {
  return (
    <header id="header" className="header d-flex align-items-center fixed-top">
      <div className="container-fluid container-xl position-relative d-flex align-items-center">
        <Link href="/" className="logo d-flex align-items-center me-auto">
          <img src="/assets/img/logo/logo.png" alt="Naaelah Saleh & Co" />
        </Link>
        <Menu />
        <Link className="btn-getstarted" href="/status-check">Status Check</Link>
      </div>
    </header>
  );
}

