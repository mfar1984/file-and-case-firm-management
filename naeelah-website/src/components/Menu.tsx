"use client";

import Link from "next/link";
import { useCallback, useEffect } from "react";
import type React from "react";

export default function Menu() {
  const onToggle = useCallback((e: React.MouseEvent) => {
    e.preventDefault();
    const parent = (e.currentTarget as HTMLElement).parentElement;
    if (!parent) return;
    parent.classList.toggle("active");
    const next = parent.nextElementSibling as HTMLElement | null;
    if (next) next.classList.toggle("dropdown-active");
    e.stopPropagation();
  }, []);

  // Flip nested submenu direction if it would overflow viewport
  useEffect(() => {
    const nav = document.getElementById("navmenu");
    if (!nav) return;
    const nestedDropdowns = Array.from(nav.querySelectorAll<HTMLLIElement>("li.dropdown li.dropdown"));

    const listeners: Array<{ li: HTMLLIElement; handler: (e: Event) => void }> = [];

    nestedDropdowns.forEach((li) => {
      const handler = () => {
        const submenu = li.querySelector<HTMLElement>(":scope > ul");
        if (!submenu) return;
        submenu.style.left = "";
        li.classList.remove("submenu-left");

        const rect = submenu.getBoundingClientRect();
        const overflowRight = rect.right > window.innerWidth - 8;
        const overflowLeft = rect.left < 8;

        if (overflowRight) {
          li.classList.add("submenu-left");
          const rect2 = submenu.getBoundingClientRect();
          if (rect2.left < 8) submenu.style.left = `${8 - rect2.left}px`;
        } else if (overflowLeft) {
          submenu.style.left = `${8 - rect.left}px`;
        }
      };
      li.addEventListener("mouseenter", handler);
      li.addEventListener("focusin", handler);
      listeners.push({ li, handler });
    });

    return () => {
      listeners.forEach(({ li, handler }) => {
        li.removeEventListener("mouseenter", handler);
        li.removeEventListener("focusin", handler);
      });
    };
  }, []);

  return (
    <nav id="navmenu" className="navmenu me-3">
      <ul>
        <li><Link href="/">Home</Link></li>
        
        <li className="dropdown">
          <Link href="/about">
            <span>About</span> 
            <i className="bi bi-chevron-down toggle-dropdown" onClick={onToggle}></i>
          </Link>
          <ul>
            <li><Link href="/about/about-us">About Us</Link></li>
            <li><Link href="/about/our-services">Our Services</Link></li>
          </ul>
        </li>

        <li className="dropdown">
          <Link href="/practice">
            <span>Practice</span> 
            <i className="bi bi-chevron-down toggle-dropdown" onClick={onToggle}></i>
          </Link>
          <ul>
            <li><Link href="/practice/civil-litigation">Civil Litigation</Link></li>
            <li><Link href="/practice/criminal-law">Criminal Law</Link></li>
            <li><Link href="/practice/conveyancing">Conveyancing</Link></li>
            <li><Link href="/practice/probate">Grant of Probate</Link></li>
          </ul>
        </li>

        <li><Link href="/contact">Contact Us</Link></li>
      </ul>
      <i className="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>
  );
}

