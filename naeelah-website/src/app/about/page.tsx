import Link from "next/link";

export default function About() {
  return (
    <>
      <section id="about-hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>About Naaelah Saleh & Co</h1>
              <p>Your trusted legal partner committed to excellence and integrity</p>
            </div>
          </div>
        </div>
      </section>

      <section id="about-content" className="about section">
        <div className="container">
          <div className="row">
            <div className="col-lg-12">
              <h2>Our Story</h2>
              <p>Naaelah Saleh & Co is a distinguished law firm established with a vision to provide exceptional legal services to individuals and businesses across Malaysia. Founded on the principles of integrity, professionalism, and client dedication, we have grown to become a trusted name in legal practice.</p>
              <p>Our firm specializes in four key practice areas: Civil Litigation, Criminal Law, Conveyancing, and Grant of Probate. Each practice area is handled by experienced legal professionals who bring deep expertise and a commitment to achieving the best possible outcomes for our clients.</p>
              <div className="d-flex mt-4">
                <Link href="/about/about-us" className="btn-get-started me-3">About Us</Link>
                <Link href="/about/our-services" className="btn-get-started">Our Services</Link>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

