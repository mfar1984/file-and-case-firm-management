"use client";

import Link from "next/link";

export default function OurServices() {
  const services = [
    {
      title: "Civil Litigation",
      icon: "bi-hammer",
      color: "#47b2e4",
      description: "Expert representation in civil disputes and commercial matters in Malaysian courts.",
      services: [
        "Contract disputes & enforcement",
        "Commercial litigation",
        "Debt recovery & judgment enforcement",
        "Property disputes",
        "Tort claims & personal injury",
        "Injunctions & urgent applications",
      ],
      link: "/practice/civil-litigation",
    },
    {
      title: "Criminal Law",
      icon: "bi-shield-check",
      color: "#ffa76e",
      description: "Comprehensive criminal defense under Malaysian Penal Code and Criminal Procedure Code.",
      services: [
        "Criminal defense (all offences)",
        "Bail applications & remand hearings",
        "Drug offences (DDA 1952)",
        "Traffic offences (RTA 1987)",
        "White collar crimes",
        "Appeals to all court levels",
      ],
      link: "/practice/criminal-law",
    },
    {
      title: "Conveyancing",
      icon: "bi-house-door",
      color: "#6fc383",
      description: "Professional property transaction services under the National Land Code 1965.",
      services: [
        "Sale & purchase agreements",
        "Property transfers (Form 14A NLC)",
        "Loan documentation & redemption",
        "Strata title matters",
        "Searches & due diligence",
        "Stamp duty & RPGT",
      ],
      link: "/practice/conveyancing",
    },
    {
      title: "Grant of Probate",
      icon: "bi-file-earmark-text",
      color: "#ff5ca1",
      description: "Estate administration and probate services under Malaysian probate law.",
      services: [
        "Grant of Probate applications",
        "Letters of Administration",
        "Small estates (below RM2M)",
        "Will drafting & review",
        "Estate distribution",
        "Muslim estates (faraid)",
      ],
      link: "/practice/probate",
    },
  ];

  return (
    <>
      {/* Hero Section */}
      <section id="services-hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>Our Legal Services</h1>
              <p>Comprehensive legal solutions tailored to your needs</p>
            </div>
          </div>
        </div>
      </section>

      {/* Introduction */}
      <section id="intro" className="about section">
        <div className="container">
          <div className="row gy-4 justify-content-center">
            <div className="col-lg-10 text-center" data-aos="fade-up">
              <h2>Expert Legal Services Across Four Key Practice Areas</h2>
              <p className="lead">
                Naaelah Saleh & Co provides comprehensive legal services to individuals and businesses across Malaysia. 
                Our experienced team of legal professionals specializes in four main practice areas, each handled with 
                the utmost professionalism, expertise, and dedication to achieving the best outcomes for our clients.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Main Services Grid */}
      <section id="services-grid" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Our Practice Areas</h2>
          <p>Click on any service to learn more about how we can help you</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            {services.map((service, index) => (
              <div key={index} className="col-lg-6" data-aos="fade-up" data-aos-delay={index * 100}>
                <div className="service-card h-100">
                  <div className="service-header" style={{borderLeftColor: service.color}}>
                    <div className="d-flex align-items-center">
                      <div className="service-icon" style={{background: service.color}}>
                        <i className={`bi ${service.icon}`}></i>
                      </div>
                      <div className="ms-3">
                        <h3>{service.title}</h3>
                      </div>
                    </div>
                  </div>
                  
                  <div className="service-body">
                    <p className="service-description">{service.description}</p>
                    <h5 className="mt-4 mb-3">Services Include:</h5>
                    <ul className="service-list">
                      {service.services.map((item, idx) => (
                        <li key={idx}>
                          <i className="bi bi-check-circle-fill" style={{color: service.color}}></i>
                          {item}
                        </li>
                      ))}
                    </ul>
                    <div className="text-end mt-4">
                      <Link href={service.link} className="btn-learn-more" style={{color: service.color}}>
                        Learn More <i className="bi bi-arrow-right"></i>
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        <style jsx>{`
          .service-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
          }
          .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
          }
          .service-header {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 30px;
            border-left: 5px solid;
          }
          .service-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
          }
          .service-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #37517e;
          }
          .service-body {
            padding: 30px;
          }
          .service-description {
            color: #666;
            font-size: 16px;
            line-height: 1.8;
          }
          .service-list {
            list-style: none;
            padding: 0;
          }
          .service-list li {
            padding: 8px 0;
            font-size: 15px;
            color: #555;
          }
          .service-list i {
            margin-right: 10px;
            font-size: 18px;
          }
          .btn-learn-more {
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
          }
          .btn-learn-more:hover {
            transform: translateX(5px);
          }
        `}</style>
      </section>

      {/* Additional Services */}
      <section id="additional-services" className="about section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Additional Legal Services</h2>
          <p>Supporting services to complement our main practice areas</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
              <div className="additional-service-box">
                <i className="bi bi-file-earmark-check"></i>
                <h4>Legal Documentation</h4>
                <p>Drafting and review of contracts, agreements, and legal documents to protect your interests.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
              <div className="additional-service-box">
                <i className="bi bi-chat-dots"></i>
                <h4>Legal Consultation</h4>
                <p>Expert legal advice and consultation on various matters under Malaysian law.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
              <div className="additional-service-box">
                <i className="bi bi-people"></i>
                <h4>Mediation & ADR</h4>
                <p>Alternative dispute resolution services including mediation and negotiation.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
              <div className="additional-service-box">
                <i className="bi bi-briefcase"></i>
                <h4>Corporate Legal Support</h4>
                <p>Legal support for businesses including contract review and compliance matters.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
              <div className="additional-service-box">
                <i className="bi bi-shield-lock"></i>
                <h4>Legal Representation</h4>
                <p>Professional representation in courts, tribunals, and government agencies.</p>
              </div>
            </div>

            <div className="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
              <div className="additional-service-box">
                <i className="bi bi-clipboard-data"></i>
                <h4>Legal Research</h4>
                <p>Comprehensive legal research and analysis for complex legal matters.</p>
              </div>
            </div>
          </div>
        </div>

        <style jsx>{`
          .additional-service-box {
            background: white;
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.08);
            height: 100%;
            text-align: center;
            transition: all 0.3s ease;
          }
          .additional-service-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 35px rgba(0, 0, 0, 0.12);
          }
          .additional-service-box i {
            font-size: 48px;
            color: #47b2e4;
            margin-bottom: 20px;
            display: block;
          }
          .additional-service-box h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #37517e;
          }
          .additional-service-box p {
            color: #666;
            margin: 0;
            line-height: 1.6;
          }
        `}</style>
      </section>

      {/* How We Work */}
      <section id="how-we-work" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>How We Work</h2>
          <p>Our process for delivering exceptional legal services</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
              <div className="process-box">
                <div className="number">01</div>
                <h4>Initial Consultation</h4>
                <p>We listen to understand your legal needs and objectives</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
              <div className="process-box">
                <div className="number">02</div>
                <h4>Case Assessment</h4>
                <p>Thorough analysis and development of legal strategy</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
              <div className="process-box">
                <div className="number">03</div>
                <h4>Action & Execution</h4>
                <p>Implement the strategy with precision and expertise</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
              <div className="process-box">
                <div className="number">04</div>
                <h4>Resolution</h4>
                <p>Achieve the best possible outcome for your case</p>
              </div>
            </div>
          </div>
        </div>

        <style jsx>{`
          .process-box {
            background: white;
            padding: 40px 25px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            height: 100%;
            transition: all 0.3s ease;
          }
          .process-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
          }
          .number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #47b2e4 0%, #2d8cc8 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            margin: 0 auto 20px;
          }
          .process-box h4 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #37517e;
          }
          .process-box p {
            color: #666;
            margin: 0;
          }
        `}</style>
      </section>

      {/* Call to Action */}
      <section id="cta" className="call-to-action section dark-background">
        <div className="container">
          <div className="row" data-aos="zoom-in">
            <div className="col-xl-9 text-center text-xl-start">
              <h3>Ready to Get Started?</h3>
              <p>Contact us today to discuss your legal needs and learn how we can help</p>
            </div>
            <div className="col-xl-3 cta-btn-container text-center">
              <Link className="cta-btn align-middle" href="/contact">Schedule a Consultation</Link>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
