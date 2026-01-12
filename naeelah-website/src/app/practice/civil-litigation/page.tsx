import Link from "next/link";

export default function CivilLitigation() {
  return (
    <>
      {/* Hero Section */}
      <section id="hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>Civil Litigation</h1>
              <p>Expert representation in civil disputes and commercial matters under Malaysian law</p>
            </div>
          </div>
        </div>
      </section>

      {/* Introduction Section */}
      <section id="intro" className="about section">
        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
              <h3>Civil Litigation Practice</h3>
              <p className="fst-italic">
                Our civil litigation practice handles comprehensive disputes in Malaysian courts, from the 
                Magistrates' Court to the Federal Court.
              </p>
              <ul>
                <li><i className="bi bi-check-circle"></i> <span>Experienced in all levels of Malaysian courts</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Strategic litigation support from pre-trial to appeals</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Expertise in Malaysian civil procedure laws</span></li>
              </ul>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <h3>Governing Legislation</h3>
              <p>Our practice is guided by:</p>
              <ul>
                <li><i className="bi bi-book"></i> <span><strong>Courts of Judicature Act 1964</strong> - Superior courts jurisdiction</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Subordinate Courts Act 1948</strong> - Lower courts procedures</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Rules of Court 2012</strong> - Civil procedure rules</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Contracts Act 1950</strong> - Contract disputes</span></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Services Section */}
      <section id="services" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Our Civil Litigation Services</h2>
          <p>Comprehensive legal representation across all areas of civil disputes</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
              <div className="service-item position-relative w-100">
                <div className="icon"><i className="bi bi-file-earmark-text"></i></div>
                <h4>Contract Disputes</h4>
                <p>Representation in breach of contract claims under the Contracts Act 1950</p>
                <ul className="mt-3">
                  <li>Sale of goods disputes</li>
                  <li>Construction contracts</li>
                  <li>Service agreements</li>
                  <li>Partnership disputes</li>
                </ul>
              </div>
            </div>

            <div className="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
              <div className="service-item position-relative w-100">
                <div className="icon"><i className="bi bi-building"></i></div>
                <h4>Commercial Litigation</h4>
                <p>Handling complex business disputes in Malaysian courts</p>
                <ul className="mt-3">
                  <li>Shareholder disputes (S.346 CA 2016)</li>
                  <li>Company winding-up</li>
                  <li>Banking litigation</li>
                  <li>Insurance claims</li>
                </ul>
              </div>
            </div>

            <div className="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
              <div className="service-item position-relative w-100">
                <div className="icon"><i className="bi bi-cash-coin"></i></div>
                <h4>Debt Recovery</h4>
                <p>Efficient recovery of outstanding debts through legal proceedings</p>
                <ul className="mt-3">
                  <li>Summary judgment (O.14 ROC)</li>
                  <li>Writ of Summons filing</li>
                  <li>Garnishee proceedings</li>
                  <li>Judgment enforcement</li>
                </ul>
              </div>
            </div>

            <div className="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="400">
              <div className="service-item position-relative w-100">
                <div className="icon"><i className="bi bi-house-door"></i></div>
                <h4>Property Disputes</h4>
                <p>Resolving landlord-tenant and property-related conflicts</p>
                <ul className="mt-3">
                  <li>Landlord-tenant disputes</li>
                  <li>Property damage claims</li>
                  <li>Trespass and nuisance</li>
                  <li>Boundary disputes</li>
                </ul>
              </div>
            </div>

            <div className="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="500">
              <div className="service-item position-relative w-100">
                <div className="icon"><i className="bi bi-shield-exclamation"></i></div>
                <h4>Tort Claims</h4>
                <p>Personal injury and negligence claims</p>
                <ul className="mt-3">
                  <li>Negligence claims</li>
                  <li>Defamation suits</li>
                  <li>Motor accident claims</li>
                  <li>Professional negligence</li>
                </ul>
              </div>
            </div>

            <div className="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="600">
              <div className="service-item position-relative w-100">
                <div className="icon"><i className="bi bi-shield-lock"></i></div>
                <h4>Injunctions</h4>
                <p>Urgent court orders to protect your interests</p>
                <ul className="mt-3">
                  <li>Interim injunctions</li>
                  <li>Mareva injunctions</li>
                  <li>Anton Piller orders</li>
                  <li>Mandatory injunctions</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Court Experience Section */}
      <section id="courts" className="about section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Malaysian Court Experience</h2>
          <p>Representing clients at all levels of the Malaysian judicial system</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div className="features-item">
                <i className="bi bi-bank" style={{color: "#47b2e4", fontSize: "48px"}}></i>
                <h3 className="mt-3">Subordinate Courts</h3>
                <p><strong>Magistrates' Court</strong><br/>Claims up to RM100,000</p>
                <p><strong>Sessions Court</strong><br/>Claims up to RM1,000,000</p>
              </div>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div className="features-item">
                <i className="bi bi-bank2" style={{color: "#ffa76e", fontSize: "48px"}}></i>
                <h3 className="mt-3">Superior Courts</h3>
                <p><strong>High Court</strong><br/>Unlimited civil jurisdiction</p>
                <p><strong>Court of Appeal & Federal Court</strong><br/>Appellate jurisdiction</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Alternative Dispute Resolution Section */}
      <section id="adr" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Alternative Dispute Resolution</h2>
          <p>Effective resolution methods outside of court</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
              <div className="service-item position-relative text-center">
                <div className="icon mx-auto"><i className="bi bi-people"></i></div>
                <h4>Mediation</h4>
                <p>Court-annexed mediation under Mediation Act 2012</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
              <div className="service-item position-relative text-center">
                <div className="icon mx-auto"><i className="bi bi-clipboard-check"></i></div>
                <h4>Arbitration</h4>
                <p>Domestic and international arbitration under Arbitration Act 2005</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
              <div className="service-item position-relative text-center">
                <div className="icon mx-auto"><i className="bi bi-chat-dots"></i></div>
                <h4>Negotiation</h4>
                <p>Pre-litigation settlement negotiations</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
              <div className="service-item position-relative text-center">
                <div className="icon mx-auto"><i className="bi bi-person-check"></i></div>
                <h4>Conciliation</h4>
                <p>Facilitated dispute resolution</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section id="cta" className="call-to-action section dark-background">
        <div className="container">
          <div className="row" data-aos="zoom-in">
            <div className="col-xl-9 text-center text-xl-start">
              <h3>Need Legal Representation?</h3>
              <p>Contact us today to discuss your civil litigation matter with our experienced legal team</p>
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
