"use client";

import { useEffect } from "react";

declare global {
  interface Window {
    Swiper: any;
  }
}

export default function SwiperInit() {
  useEffect(() => {
    // Initialize Swiper instances after component mount
    const initSwiper = () => {
      if (typeof window !== "undefined" && window.Swiper) {
        document.querySelectorAll(".init-swiper").forEach((el) => {
          const configAttr = el.querySelector(".swiper-config");
          if (configAttr) {
            try {
              const config = JSON.parse(configAttr.innerHTML || "{}");
              new window.Swiper(el, config);
            } catch (e) {
              console.error("Swiper config parse error:", e);
            }
          }
        });
      }
    };

    // Wait for Swiper library to load
    const checkSwiper = setInterval(() => {
      if (typeof window !== "undefined" && window.Swiper) {
        clearInterval(checkSwiper);
        initSwiper();
      }
    }, 100);

    return () => clearInterval(checkSwiper);
  }, []);

  return null;
}

