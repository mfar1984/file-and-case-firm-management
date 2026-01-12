"use client";

import { useEffect, useState } from "react";
import Link from "next/link";

export default function Topbar() {
  const [now, setNow] = useState<Date>(new Date());
  const [mounted, setMounted] = useState(false);
  const [loginMenuOpen, setLoginMenuOpen] = useState(false);

  useEffect(() => {
    const id = setInterval(() => setNow(new Date()), 1000);
    return () => clearInterval(id);
  }, []);

  useEffect(() => {
    setMounted(true);
  }, []);

  const dateStr = new Intl.DateTimeFormat("en-GB", {
    weekday: "short",
    day: "2-digit",
    month: "long",
    year: "numeric",
  }).format(now);

  const timeStr = new Intl.DateTimeFormat("en-GB", {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: true,
  }).format(now);

  return (
    <>
      {/* Login Menu Popup */}
      {loginMenuOpen && (
        <>
          {/* Backdrop */}
          <div 
            style={{
              position: 'fixed',
              top: 0,
              left: 0,
              right: 0,
              bottom: 0,
              zIndex: 1030,
            }}
            onClick={() => setLoginMenuOpen(false)}
          />
          
          {/* Menu */}
          <div 
            className="position-fixed" 
            style={{
              top: '40px',
              right: '20px',
              zIndex: 1031,
              background: 'white',
              borderRadius: '8px',
              boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
              minWidth: '280px',
              animation: 'fadeInDown 0.3s ease-out'
            }}
          >
            <div className="p-3 border-bottom">
              <div className="d-flex justify-content-between align-items-center">
                <h6 className="mb-0 fw-bold">Select Office</h6>
                <button 
                  onClick={() => setLoginMenuOpen(false)}
                  className="btn-close btn-sm"
                  aria-label="Close"
                ></button>
              </div>
            </div>
            <div className="p-2">
              <a
                href="https://hq.naaelahsaleh.co"
                target="_blank"
                rel="noopener noreferrer"
                className="btn w-100 text-start mb-2 d-flex align-items-center gap-3 border text-decoration-none"
                style={{
                  padding: '12px',
                  borderRadius: '8px',
                  transition: 'all 0.2s',
                  background: 'white',
                  color: 'inherit'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.background = '#47b2e4';
                  e.currentTarget.style.color = 'white';
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.background = 'white';
                  e.currentTarget.style.color = 'inherit';
                }}
              >
                <i className="bi bi-building" style={{fontSize: '24px', color: '#47b2e4'}}></i>
                <div className="flex-grow-1">
                  <div className="fw-bold">HQ - Sg Pelek</div>
                  <div className="text-muted small">Main Office</div>
                </div>
              </a>
              
              <a
                href="https://sendayan.naaelahsaleh.co"
                target="_blank"
                rel="noopener noreferrer"
                className="btn w-100 text-start mb-2 d-flex align-items-center gap-3 border text-decoration-none"
                style={{
                  padding: '12px',
                  borderRadius: '8px',
                  transition: 'all 0.2s',
                  background: 'white',
                  color: 'inherit'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.background = '#6fc383';
                  e.currentTarget.style.color = 'white';
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.background = 'white';
                  e.currentTarget.style.color = 'inherit';
                }}
              >
                <i className="bi bi-building" style={{fontSize: '24px', color: '#6fc383'}}></i>
                <div className="flex-grow-1">
                  <div className="fw-bold">Sendayan Branch</div>
                  <div className="text-muted small">N. Sembilan Office</div>
                </div>
              </a>
            </div>
          </div>
        </>
      )}
    
      <div id="topbar" className="topbar d-flex align-items-center text-white">
        <div className="container d-flex justify-content-between align-items-center">
          {/* Left: Date • Live Time */}
          <div className="d-flex align-items-center">
            <span suppressHydrationWarning>{mounted ? dateStr : ''}</span>
            <span className="px-2">•</span>
            <span suppressHydrationWarning>{mounted ? timeStr : ''}</span>
          </div>

          {/* Right: Email and Login */}
          <div className="d-flex align-items-center gap-3">
            <a href="mailto:general@naaelahsaleh.co" className="text-white text-decoration-none d-flex align-items-center">
              <i className="bi bi-envelope me-2"></i>
              general@naaelahsaleh.co
            </a>
            <button 
              onClick={() => setLoginMenuOpen(!loginMenuOpen)}
              className="text-white text-decoration-none d-flex align-items-center border-0 bg-transparent"
              style={{cursor: 'pointer'}}
            >
              <i className="bi bi-person-circle me-2"></i>
              Login
            </button>
          </div>
        </div>
      </div>
      
      <style jsx>{`
        @keyframes fadeInDown {
          from {
            opacity: 0;
            transform: translateY(-10px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
      `}</style>
    </>
  );
}

