"use client";

import { usePathname } from "next/navigation";
import { useEffect } from "react";

export default function BodyClass() {
  const pathname = usePathname();

  useEffect(() => {
    // Add index-page class to body for ALL pages to get transparent header
    document.body.classList.add("index-page");
    
    // Cleanup on unmount
    return () => {
      document.body.classList.remove("index-page");
    };
  }, [pathname]);

  useEffect(() => {
    // Mobile nav toggle
    const mobileToggle = document.querySelector(".mobile-nav-toggle");
    if (!mobileToggle) return;

    const handleMobileToggle = () => {
      document.body.classList.toggle("mobile-nav-active");
      mobileToggle.classList.toggle("bi-list");
      mobileToggle.classList.toggle("bi-x");
    };

    mobileToggle.addEventListener("click", handleMobileToggle);

    // Close mobile nav on link clicks
    const closeNav = () => {
      if (document.body.classList.contains("mobile-nav-active")) {
        document.body.classList.remove("mobile-nav-active");
        mobileToggle.classList.remove("bi-x");
        mobileToggle.classList.add("bi-list");
      }
    };

    document.querySelectorAll("#navmenu a").forEach((link) => {
      link.addEventListener("click", closeNav);
    });

    // Scroll handling
    const handleScroll = () => {
      const header = document.getElementById("header");
      const topbar = document.getElementById("topbar");
      if (header) {
        if (window.scrollY > 100) {
          header.classList.add("sticked");
          document.body.classList.add("scrolled");
        } else {
          header.classList.remove("sticked");
          document.body.classList.remove("scrolled");
        }
      }
    };

    // Scroll to top button
    const scrollTop = document.querySelector(".scroll-top");
    if (scrollTop) {
      const toggleScrollTop = () => {
        if (window.scrollY > 100) {
          scrollTop.classList.add("active");
        } else {
          scrollTop.classList.remove("active");
        }
      };

      scrollTop.addEventListener("click", (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
      });

      window.addEventListener("scroll", toggleScrollTop);
      toggleScrollTop();
    }

    window.addEventListener("scroll", handleScroll);
    handleScroll();

    return () => {
      mobileToggle.removeEventListener("click", handleMobileToggle);
      window.removeEventListener("scroll", handleScroll);
    };
  }, [pathname]);

  return null;
}

