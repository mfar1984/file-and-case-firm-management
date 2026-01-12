import Link from "next/link";
import StatsCounter from "@/components/StatsCounter";

export default function Home() {
  return (
    <>
      {/* Hero Section */}
      <section id="hero" className="hero section dark-background" style={{backgroundImage: 'url(/assets/img/background/home.png)', backgroundSize: 'cover', backgroundPosition: 'center', backgroundRepeat: 'no-repeat'}}>
        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
              <h1>Excellence in Legal Practice</h1>
              <p>Your Trusted Legal Partner in Malaysia</p>
              <p className="lead">Naaelah Saleh & Co provides comprehensive legal services with integrity, professionalism, and dedication to our clients' success.</p>
              <div className="d-flex">
                <Link href="/contact" className="btn-get-started">Get Consultation</Link>
                <Link href="/status-check" className="btn-watch-video d-flex align-items-center ms-4">
                  <i className="bi bi-search"></i><span>Check Case Status</span>
                </Link>
              </div>
            </div>
            <div className="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
            </div>
          </div>
        </div>
      </section>

      {/* Practice Areas Section */}
      <section id="services" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Our Practice Areas</h2>
          <p>We specialize in four key areas of legal practice, providing expert guidance and representation</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
              <div className="service-item position-relative text-center">
                <div className="icon mx-auto"><i className="bi bi-hammer"></i></div>
                <h4><Link href="/practice/civil-litigation" className="stretched-link">Civil Litigation</Link></h4>
                <p>Expert representation in civil disputes, contract matters, and commercial litigation.</p>
              </div>
            </div>

            <div className="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
              <div className="service-item position-relative text-center">
                <div className="icon mx-auto"><i className="bi bi-shield-check"></i></div>
                <h4><Link href="/practice/criminal-law" className="stretched-link">Criminal Law</Link></h4>
                <p>Comprehensive criminal defense services with experienced legal representation.</p>
              </div>
            </div>

            <div className="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
              <div className="service-item position-relative text-center">
                <div className="icon mx-auto"><i className="bi bi-house-door"></i></div>
                <h4><Link href="/practice/conveyancing" className="stretched-link">Conveyancing</Link></h4>
                <p>Property transaction services including sales, purchases, and transfers.</p>
              </div>
            </div>

            <div className="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="400">
              <div className="service-item position-relative text-center">
                <div className="icon mx-auto"><i className="bi bi-file-earmark-text"></i></div>
                <h4><Link href="/practice/probate" className="stretched-link">Grant of Probate</Link></h4>
                <p>Estate administration and probate matters handled with care and professionalism.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section id="about" className="about section">
        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
              <p className="who-we-are">Who We Are</p>
              <h3>Naaelah Saleh & Co</h3>
              <p className="fst-italic">
                A distinguished law firm committed to providing exceptional legal services to individuals and businesses across Malaysia.
              </p>
              <ul>
                <li><i className="bi bi-check-circle"></i> <span>Expert legal team with deep knowledge across various practice areas</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Personalized attention and tailored solutions for every client</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Proven track record of successful outcomes and satisfied clients</span></li>
              </ul>
              <Link href="/about/about-us" className="read-more"><span>Read More</span><i className="bi bi-arrow-right"></i></Link>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div className="row gy-3">
                <div className="col-12">
                  <div className="info-box" style={{background: 'linear-gradient(135deg, #47b2e4 0%, #37a3d4 100%)', padding: '20px 25px', borderRadius: '10px', color: 'white'}}>
                    <div className="d-flex align-items-center">
                      <i className="bi bi-building" style={{fontSize: '40px', marginRight: '15px'}}></i>
                      <div>
                        <h4 className="mb-1" style={{color: 'white', fontSize: '18px'}}>Established Practice</h4>
                        <p className="mb-0" style={{fontSize: '13px', opacity: '0.9'}}>Serving clients across Malaysia</p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div className="col-12">
                  <div className="info-box" style={{background: 'linear-gradient(135deg, #6fc383 0%, #5fb373 100%)', padding: '20px 25px', borderRadius: '10px', color: 'white'}}>
                    <div className="d-flex align-items-center">
                      <i className="bi bi-trophy" style={{fontSize: '40px', marginRight: '15px'}}></i>
                      <div>
                        <h4 className="mb-1" style={{color: 'white', fontSize: '18px'}}>Proven Excellence</h4>
                        <p className="mb-0" style={{fontSize: '13px', opacity: '0.9'}}>Track record of successful cases</p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div className="col-12">
                  <div className="info-box" style={{background: 'linear-gradient(135deg, #ffa76e 0%, #ff975e 100%)', padding: '20px 25px', borderRadius: '10px', color: 'white'}}>
                    <div className="d-flex align-items-center">
                      <i className="bi bi-shield-check" style={{fontSize: '40px', marginRight: '15px'}}></i>
                      <div>
                        <h4 className="mb-1" style={{color: 'white', fontSize: '18px'}}>Client Protection</h4>
                        <p className="mb-0" style={{fontSize: '13px', opacity: '0.9'}}>Your rights and interests safeguarded</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Stats Counter Section */}
      <StatsCounter />

      {/* Why Choose Us Section */}
      <section id="features" className="features section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Why Choose Naaelah Saleh & Co</h2>
          <p>What sets us apart in providing legal services</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
              <div className="features-item">
                <i className="bi bi-award" style={{color: "#47b2e4"}}></i>
                <h3><a href="" className="stretched-link">Proven Track Record</a></h3>
                <p>Years of successful outcomes in Malaysian courts across all practice areas with satisfied clients.</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
              <div className="features-item">
                <i className="bi bi-people" style={{color: "#ffa76e"}}></i>
                <h3><a href="" className="stretched-link">Experienced Team</a></h3>
                <p>Seasoned legal professionals with extensive knowledge of Malaysian law and court procedures.</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
              <div className="features-item">
                <i className="bi bi-person-heart" style={{color: "#6fc383"}}></i>
                <h3><a href="" className="stretched-link">Client-Centered</a></h3>
                <p>Personalized legal solutions tailored to your unique situation and objectives.</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
              <div className="features-item">
                <i className="bi bi-telephone" style={{color: "#ff5ca1"}}></i>
                <h3><a href="" className="stretched-link">Always Accessible</a></h3>
                <p>Open communication channels and regular updates throughout your legal matter.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Our Approach Section */}
      <section id="approach" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Our Approach to Legal Service</h2>
          <p>How we work to achieve the best outcomes for our clients</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div className="service-item d-flex position-relative h-100">
                <i className="bi bi-1-circle flex-shrink-0"></i>
                <div>
                  <h4 className="title">Understanding Your Needs</h4>
                  <p className="description">We begin by listening carefully to understand your situation, concerns, and objectives. Every client's case is unique, and we take the time to fully comprehend your legal needs.</p>
                </div>
              </div>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div className="service-item d-flex position-relative h-100">
                <i className="bi bi-2-circle flex-shrink-0"></i>
                <div>
                  <h4 className="title">Strategic Planning</h4>
                  <p className="description">We develop a comprehensive legal strategy tailored to your case, considering all available options and potential outcomes to achieve the best results.</p>
                </div>
              </div>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="300">
              <div className="service-item d-flex position-relative h-100">
                <i className="bi bi-3-circle flex-shrink-0"></i>
                <div>
                  <h4 className="title">Expert Execution</h4>
                  <p className="description">Our experienced team executes the legal strategy with precision, leveraging our expertise in Malaysian law and court procedures to protect your interests.</p>
                </div>
              </div>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="400">
              <div className="service-item d-flex position-relative h-100">
                <i className="bi bi-4-circle flex-shrink-0"></i>
                <div>
                  <h4 className="title">Clear Communication</h4>
                  <p className="description">We keep you informed at every stage, explaining legal processes in clear terms and providing regular updates on case progress and developments.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Areas We Serve */}
      <section id="areas-served" className="about section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Areas We Serve</h2>
          <p>Providing legal services across Malaysia</p>
        </div>

        <div className="container">
          <div className="row gy-4 justify-content-center">
            <div className="col-lg-10" data-aos="fade-up" data-aos-delay="100">
              <div className="areas-box p-4 bg-light rounded">
                <div className="row">
                  <div className="col-md-4 mb-3">
                    <h5><i className="bi bi-geo-alt-fill me-2 text-primary"></i>Kuala Lumpur</h5>
                    <ul className="list-unstyled ms-4">
                      <li>High Court</li>
                      <li>Sessions Court</li>
                      <li>Magistrates Court</li>
                    </ul>
                  </div>
                  <div className="col-md-4 mb-3">
                    <h5><i className="bi bi-geo-alt-fill me-2 text-primary"></i>Selangor</h5>
                    <ul className="list-unstyled ms-4">
                      <li>Shah Alam Courts</li>
                      <li>Petaling Jaya</li>
                      <li>Klang Valley</li>
                    </ul>
                  </div>
                  <div className="col-md-4 mb-3">
                    <h5><i className="bi bi-geo-alt-fill me-2 text-primary"></i>All State Courts</h5>
                    <ul className="list-unstyled ms-4">
                      <li>Except Sabah & Sarawak</li>
                      <li>Court of Appeal</li>
                      <li>Federal Court</li>
                    </ul>
                  </div>
                </div>
                <p className="text-center mt-3 mb-0 text-muted">
                  <em>We represent clients in all Malaysian courts and handle matters nationwide</em>
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Call To Action Section */}
      <section id="call-to-action" className="call-to-action section dark-background">
        <div className="container">
          <div className="row" data-aos="zoom-in" data-aos-delay="100">
            <div className="col-xl-9 text-center text-xl-start">
              <h3>Need Legal Assistance?</h3>
              <p>Contact us today for a consultation. Our experienced legal team is ready to help you with your legal matters.</p>
            </div>
            <div className="col-xl-3 cta-btn-container text-center">
              <Link className="cta-btn align-middle" href="/contact">Get Started</Link>
            </div>
          </div>
    </div>
      </section>
    </>
  );
}
