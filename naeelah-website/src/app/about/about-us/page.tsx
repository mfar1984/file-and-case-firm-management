"use client";

import Link from "next/link";

export default function AboutUs() {
  return (
    <>
      {/* Hero Section */}
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

      {/* Company Overview */}
      <section id="overview" className="about section">
        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
              <p className="who-we-are">Who We Are</p>
              <h3>A Distinguished Law Firm in Malaysia</h3>
              <p className="fst-italic">
                Naaelah Saleh & Co is a distinguished law firm established with a vision to provide exceptional 
                legal services to individuals and businesses across Malaysia.
              </p>
              <p>
                Founded on the principles of integrity, professionalism, and client dedication, we have grown to 
                become a trusted name in legal practice. Our firm specializes in four key practice areas: Civil 
                Litigation, Criminal Law, Conveyancing, and Grant of Probate.
              </p>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <p>
                Each practice area is handled by experienced legal professionals who bring deep expertise and a 
                commitment to achieving the best possible outcomes for our clients.
              </p>
              <ul>
                <li><i className="bi bi-check-circle"></i> <span>Comprehensive legal solutions tailored to your needs</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Experienced team with proven track record</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Client-focused approach to legal practice</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Adherence to highest ethical standards</span></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Our Mission & Vision */}
      <section id="mission-vision" className="services section light-background">
        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div className="card border-0 shadow-lg h-100">
                <div className="card-body p-5 text-center">
                  <div className="icon-box mb-4">
                    <i className="bi bi-bullseye" style={{fontSize: "64px", color: "#47b2e4"}}></i>
                  </div>
                  <h3 className="mb-4">Our Mission</h3>
                  <p className="lead">
                    To provide exceptional legal services with integrity, professionalism, and dedication, 
                    ensuring that every client receives personalized attention and expert guidance in resolving 
                    their legal matters.
                  </p>
                </div>
              </div>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div className="card border-0 shadow-lg h-100">
                <div className="card-body p-5 text-center">
                  <div className="icon-box mb-4">
                    <i className="bi bi-eye" style={{fontSize: "64px", color: "#ffa76e"}}></i>
                  </div>
                  <h3 className="mb-4">Our Vision</h3>
                  <p className="lead">
                    To be recognized as a leading law firm in Malaysia, known for our commitment to excellence, 
                    ethical practice, and unwavering dedication to protecting our clients' interests and 
                    achieving their legal objectives.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Core Values */}
      <section id="values" className="about section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Our Core Values</h2>
          <p>The principles that guide everything we do</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
              <div className="value-box text-center p-4">
                <div className="icon mx-auto mb-3">
                  <i className="bi bi-shield-check" style={{fontSize: "48px", color: "#47b2e4"}}></i>
                </div>
                <h4>Integrity</h4>
                <p>We uphold the highest ethical standards in all our professional dealings, ensuring honesty and transparency with our clients.</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
              <div className="value-box text-center p-4">
                <div className="icon mx-auto mb-3">
                  <i className="bi bi-lightning" style={{fontSize: "48px", color: "#ffa76e"}}></i>
                </div>
                <h4>Excellence</h4>
                <p>Committed to delivering superior legal services and exceptional results through continuous learning and improvement.</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
              <div className="value-box text-center p-4">
                <div className="icon mx-auto mb-3">
                  <i className="bi bi-people" style={{fontSize: "48px", color: "#6fc383"}}></i>
                </div>
                <h4>Client Focus</h4>
                <p>Your needs and objectives are at the heart of everything we do. We provide personalized attention to every client.</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
              <div className="value-box text-center p-4">
                <div className="icon mx-auto mb-3">
                  <i className="bi bi-star" style={{fontSize: "48px", color: "#ff5ca1"}}></i>
                </div>
                <h4>Professionalism</h4>
                <p>Maintaining the highest professional standards in legal practice, ensuring quality service delivery at all times.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Why Choose Us */}
      <section id="why-choose" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Why Choose Naaelah Saleh & Co?</h2>
          <p>What sets us apart from other law firms</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="100">
              <div className="feature-box">
                <i className="bi bi-award-fill"></i>
                <h4>Proven Track Record</h4>
                <p>Years of successful case outcomes and satisfied clients across various practice areas.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
              <div className="feature-box">
                <i className="bi bi-people-fill"></i>
                <h4>Experienced Team</h4>
                <p>Our lawyers have extensive experience in Malaysian law and court procedures.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
              <div className="feature-box">
                <i className="bi bi-person-heart"></i>
                <h4>Personalized Service</h4>
                <p>We take time to understand your unique situation and provide tailored legal solutions.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="400">
              <div className="feature-box">
                <i className="bi bi-telephone-fill"></i>
                <h4>Accessible Communication</h4>
                <p>We maintain open lines of communication and keep you informed throughout your legal matter.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="500">
              <div className="feature-box">
                <i className="bi bi-currency-dollar"></i>
                <h4>Transparent Pricing</h4>
                <p>Clear fee structures with no hidden costs, based on official remuneration orders.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="600">
              <div className="feature-box">
                <i className="bi bi-clock-fill"></i>
                <h4>Timely Service</h4>
                <p>We understand the importance of time and work efficiently to resolve your legal matters.</p>
              </div>
            </div>
          </div>
        </div>

        <style jsx>{`
          .feature-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
            height: 100%;
            transition: all 0.3s ease;
          }
          .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
          }
          .feature-box i {
            font-size: 48px;
            color: #47b2e4;
            margin-bottom: 15px;
            display: block;
          }
          .feature-box h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #37517e;
          }
          .feature-box p {
            color: #667;
            margin: 0;
          }
          .value-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.08);
            height: 100%;
            transition: all 0.3s ease;
          }
          .value-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 35px rgba(0, 0, 0, 0.12);
          }
        `}</style>
      </section>

      {/* Our Commitment */}
      <section id="commitment" className="about section">
        <div className="container">
          <div className="row gy-4 justify-content-center">
            <div className="col-lg-10" data-aos="fade-up">
              <div className="commitment-box p-5 bg-primary bg-gradient text-white rounded">
                <h2 className="text-white text-center mb-4">Our Commitment to You</h2>
                <div className="row">
                  <div className="col-md-6">
                    <ul className="commitment-list">
                      <li><i className="bi bi-check-circle-fill me-2"></i>Protecting your legal rights and interests</li>
                      <li><i className="bi bi-check-circle-fill me-2"></i>Providing clear and honest legal advice</li>
                      <li><i className="bi bi-check-circle-fill me-2"></i>Maintaining strict confidentiality</li>
                      <li><i className="bi bi-check-circle-fill me-2"></i>Acting with integrity and professionalism</li>
                    </ul>
                  </div>
                  <div className="col-md-6">
                    <ul className="commitment-list">
                      <li><i className="bi bi-check-circle-fill me-2"></i>Delivering results-oriented legal solutions</li>
                      <li><i className="bi bi-check-circle-fill me-2"></i>Keeping you informed at every stage</li>
                      <li><i className="bi bi-check-circle-fill me-2"></i>Working diligently on your behalf</li>
                      <li><i className="bi bi-check-circle-fill me-2"></i>Building long-term client relationships</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <style jsx>{`
          .commitment-list {
            list-style: none;
            padding: 0;
          }
          .commitment-list li {
            margin-bottom: 15px;
            font-size: 16px;
          }
        `}</style>
      </section>

      {/* Call to Action */}
      <section id="cta" className="call-to-action section dark-background">
        <div className="container">
          <div className="row" data-aos="zoom-in">
            <div className="col-xl-9 text-center text-xl-start">
              <h3>Ready to Work With Us?</h3>
              <p>Contact us today for a consultation. We're here to help you navigate your legal matters with confidence.</p>
            </div>
            <div className="col-xl-3 cta-btn-container text-center">
              <Link className="cta-btn align-middle" href="/contact">Contact Us Now</Link>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
