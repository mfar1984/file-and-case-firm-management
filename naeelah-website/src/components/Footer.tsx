"use client";

import Link from "next/link";

export default function Footer() {
  return (
    <footer id="footer" className="footer">
      <div className="container footer-top">
        <div className="row gy-4">
          <div className="col-lg-3 col-md-6 footer-about">
            <Link href="/" className="d-flex align-items-center">
              <img src="/assets/img/logo/logo.png" alt="Naaelah Saleh & Co" />
            </Link>
            <div className="footer-contact pt-3">
              <p>
                <strong>HQ - Sg Pelek</strong> - <a href="https://maps.app.goo.gl/pUgz2zTDa7Jz6bBG7" target="_blank" rel="noopener noreferrer" style={{color: 'var(--accent-color)', textDecoration: 'none', fontSize: '13px'}}>Maps</a>
              </p>
              
              <p className="mt-2">
                <strong>Sendayan Branch</strong> - <a href="https://maps.app.goo.gl/RZnUmeTpSqzKB3MX9" target="_blank" rel="noopener noreferrer" style={{color: 'var(--accent-color)', textDecoration: 'none', fontSize: '13px'}}>Maps</a>
              </p>
            </div>
          </div>

          <div className="col-lg-3 col-md-6 footer-links">
            <h4>Quick Links</h4>
            <ul>
              <li><i className="bi bi-chevron-right"></i> <Link href="/">Home</Link></li>
              <li><i className="bi bi-chevron-right"></i> <Link href="/about/about-us">About Us</Link></li>
              <li><i className="bi bi-chevron-right"></i> <Link href="/about/our-services">Our Services</Link></li>
              <li><i className="bi bi-chevron-right"></i> <Link href="/contact">Contact Us</Link></li>
            </ul>
          </div>

          <div className="col-lg-3 col-md-6 footer-links">
            <h4>Practice Areas</h4>
            <ul>
              <li><i className="bi bi-chevron-right"></i> <Link href="/practice/civil-litigation">Civil Litigation</Link></li>
              <li><i className="bi bi-chevron-right"></i> <Link href="/practice/criminal-law">Criminal Law</Link></li>
              <li><i className="bi bi-chevron-right"></i> <Link href="/practice/conveyancing">Conveyancing</Link></li>
              <li><i className="bi bi-chevron-right"></i> <Link href="/practice/probate">Grant of Probate</Link></li>
            </ul>
          </div>

          <div className="col-lg-3 col-md-6">
            <h4>Follow Us</h4>
            <p className="mb-3">Connect with us</p>
            <div className="social-links d-flex">
              <a href="https://www.facebook.com/share/17aidqkmcN/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer" title="Facebook"><i className="bi bi-facebook"></i></a>
              <a href="mailto:general@naaelahsaleh.co" title="Email"><i className="bi bi-envelope"></i></a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}

