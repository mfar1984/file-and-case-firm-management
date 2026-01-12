"use client";

import Link from "next/link";

export default function CriminalLaw() {
  return (
    <>
      {/* Hero Section */}
      <section id="hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>Criminal Law</h1>
              <p>Dedicated criminal defense under the Malaysian Penal Code and Criminal Procedure Code</p>
            </div>
          </div>
        </div>
      </section>

      {/* Introduction Section with Alert */}
      <section id="intro" className="about section">
        <div className="container">
          <div className="row gy-4 justify-content-center">
            <div className="col-lg-10">
              <div className="alert alert-danger" data-aos="fade-up">
                <div className="d-flex align-items-center">
                  <i className="bi bi-exclamation-triangle-fill" style={{fontSize: "48px", marginRight: "20px"}}></i>
                  <div>
                    <h4 className="mb-2"><strong>Arrested or Under Investigation?</strong></h4>
                    <p className="mb-2">If you are under police investigation or have been arrested:</p>
                    <ul className="mb-0">
                      <li><strong>Remain silent</strong> - Do not give any statement without a lawyer</li>
                      <li><strong>Request a lawyer immediately</strong> - You have the right under Article 5(3) Federal Constitution</li>
                      <li><strong>Contact us 24/7</strong> - We provide emergency police station representation</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="row gy-4 mt-4">
            <div className="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
              <h3>Criminal Defense Services</h3>
              <p className="fst-italic">
                Comprehensive defense for individuals and corporations facing criminal charges under Malaysian law.
              </p>
              <ul>
                <li><i className="bi bi-check-circle"></i> <span>24/7 police station representation</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Experienced trial lawyers</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Protection of constitutional rights</span></li>
                <li><i className="bi bi-check-circle"></i> <span>All levels of Malaysian courts</span></li>
              </ul>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <h3>Governing Legislation</h3>
              <ul>
                <li><i className="bi bi-book"></i> <span><strong>Penal Code (Act 574)</strong> - Criminal offences</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Criminal Procedure Code (Act 593)</strong> - Court procedures</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Dangerous Drugs Act 1952</strong> - Drug offences</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Road Transport Act 1987</strong> - Traffic offences</span></li>
                <li><i className="bi bi-book"></i> <span><strong>MACC Act 2009</strong> - Corruption cases</span></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Criminal Offences - Tab Style */}
      <section id="offences" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Criminal Offences We Defend</h2>
          <p>Expert defense across all categories of criminal charges in Malaysia</p>
        </div>

        <div className="container">
          <div className="row gy-4">
            {/* Left Column */}
            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div className="card border-0 shadow-sm mb-4">
                <div className="card-header bg-danger text-white">
                  <h4 className="mb-0"><i className="bi bi-exclamation-triangle me-2"></i>General Criminal Offences</h4>
                </div>
                <div className="card-body">
                  <div className="row">
                    <div className="col-6">
                      <ul className="list-unstyled">
                        <li><i className="bi bi-arrow-right-circle-fill text-danger me-2"></i>Theft (S.378-409)</li>
                        <li><i className="bi bi-arrow-right-circle-fill text-danger me-2"></i>Criminal Breach of Trust</li>
                        <li><i className="bi bi-arrow-right-circle-fill text-danger me-2"></i>Cheating (S.415-424)</li>
                      </ul>
                    </div>
                    <div className="col-6">
                      <ul className="list-unstyled">
                        <li><i className="bi bi-arrow-right-circle-fill text-danger me-2"></i>Assault & Hurt</li>
                        <li><i className="bi bi-arrow-right-circle-fill text-danger me-2"></i>House-breaking</li>
                        <li><i className="bi bi-arrow-right-circle-fill text-danger me-2"></i>Robbery</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div className="card border-0 shadow-sm mb-4">
                <div className="card-header bg-warning text-dark">
                  <h4 className="mb-0"><i className="bi bi-prescription2 me-2"></i>Drug Offences</h4>
                </div>
                <div className="card-body">
                  <ul className="list-unstyled">
                    <li><i className="bi bi-arrow-right-circle-fill text-warning me-2"></i><strong>Drug Possession</strong> (S.12 DDA 1952)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-warning me-2"></i><strong>Drug Trafficking</strong> (S.39B DDA 1952)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-warning me-2"></i>Presumption Cases (S.37 & 39A)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-warning me-2"></i>Drug Paraphernalia</li>
                  </ul>
                </div>
              </div>

              <div className="card border-0 shadow-sm">
                <div className="card-header bg-primary text-white">
                  <h4 className="mb-0"><i className="bi bi-car-front me-2"></i>Traffic Offences</h4>
                </div>
                <div className="card-body">
                  <ul className="list-unstyled">
                    <li><i className="bi bi-arrow-right-circle-fill text-primary me-2"></i>Reckless/Dangerous Driving (S.41/42 RTA)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-primary me-2"></i>Driving Under Influence (S.45A RTA)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-primary me-2"></i>Causing Death by Driving</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-primary me-2"></i>No License/Insurance</li>
                  </ul>
                </div>
              </div>
            </div>

            {/* Right Column */}
            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div className="card border-0 shadow-sm mb-4">
                <div className="card-header bg-info text-white">
                  <h4 className="mb-0"><i className="bi bi-briefcase me-2"></i>White Collar Crimes</h4>
                </div>
                <div className="card-body">
                  <ul className="list-unstyled">
                    <li><i className="bi bi-arrow-right-circle-fill text-info me-2"></i>CBT by Agents/Employees (S.408/409)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-info me-2"></i>Corruption (MACC Act 2009)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-info me-2"></i>Companies Act Offences</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-info me-2"></i>Money Laundering (AMLA 2001)</li>
                  </ul>
                </div>
              </div>

              <div className="card border-0 shadow-sm mb-4">
                <div className="card-header bg-secondary text-white">
                  <h4 className="mb-0"><i className="bi bi-file-text me-2"></i>Statutory Offences</h4>
                </div>
                <div className="card-body">
                  <ul className="list-unstyled">
                    <li><i className="bi bi-arrow-right-circle-fill text-secondary me-2"></i>Immigration Act 1959/63</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-secondary me-2"></i>Customs Act 1967</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-secondary me-2"></i>POCA 1959</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-secondary me-2"></i>SOSMA 2012</li>
                  </ul>
                </div>
              </div>

              <div className="card border-0 shadow-sm">
                <div className="card-header bg-dark text-white">
                  <h4 className="mb-0"><i className="bi bi-person-x me-2"></i>Sexual Offences</h4>
                </div>
                <div className="card-body">
                  <ul className="list-unstyled">
                    <li><i className="bi bi-arrow-right-circle-fill text-dark me-2"></i>Rape (S.375/376 Penal Code)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-dark me-2"></i>Sexual Assault</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-dark me-2"></i>Outraging Modesty (S.354)</li>
                    <li><i className="bi bi-arrow-right-circle-fill text-dark me-2"></i>Child Sexual Offences (SOAC 2017)</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Defense Services Timeline */}
      <section id="defense-timeline" className="about section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Our Defense Process</h2>
          <p>Complete legal support from investigation to appeal</p>
        </div>

        <div className="container">
          <div className="row">
            <div className="col-lg-12">
              {/* Timeline */}
              <div className="timeline" data-aos="fade-up">
                <div className="timeline-item">
                  <div className="timeline-badge bg-danger"><i className="bi bi-shield-fill-exclamation"></i></div>
                  <div className="timeline-panel">
                    <div className="timeline-heading">
                      <h4>Pre-Trial Services</h4>
                    </div>
                    <div className="timeline-body">
                      <ul>
                        <li>Police station representation (24/7 emergency service)</li>
                        <li>Bail applications under Section 388 CPC</li>
                        <li>Remand hearings (Section 117 CPC)</li>
                        <li>Police report lodging and follow-up</li>
                        <li>Legal advice during investigation</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div className="timeline-item">
                  <div className="timeline-badge bg-primary"><i className="bi bi-bank"></i></div>
                  <div className="timeline-panel">
                    <div className="timeline-heading">
                      <h4>Court Proceedings</h4>
                    </div>
                    <div className="timeline-body">
                      <ul>
                        <li>Mention and case management</li>
                        <li>Plea bargaining negotiations with prosecution</li>
                        <li>Full trial representation at all court levels</li>
                        <li>Expert witness examination and cross-examination</li>
                        <li>Mitigation on sentencing</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div className="timeline-item">
                  <div className="timeline-badge bg-success"><i className="bi bi-arrow-up-circle"></i></div>
                  <div className="timeline-panel">
                    <div className="timeline-heading">
                      <h4>Appeals & Reviews</h4>
                    </div>
                    <div className="timeline-body">
                      <ul>
                        <li>Appeals to High Court, Court of Appeal & Federal Court</li>
                        <li>Stay of execution applications</li>
                        <li>Revision applications (Section 323 CPC)</li>
                        <li>Review of conviction or acquittal</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div className="timeline-item">
                  <div className="timeline-badge bg-warning"><i className="bi bi-file-earmark-check"></i></div>
                  <div className="timeline-panel">
                    <div className="timeline-heading">
                      <h4>Post-Conviction Relief</h4>
                    </div>
                    <div className="timeline-body">
                      <ul>
                        <li>Discharge Not Amounting to Acquittal (DNAA) applications</li>
                        <li>Representation orders for legal aid</li>
                        <li>Expungement of criminal records</li>
                        <li>Pardon applications to Yang di-Pertuan Agong</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <style jsx>{`
                .timeline {
                  position: relative;
                  padding: 20px 0;
                }
                .timeline:before {
                  content: '';
                  position: absolute;
                  top: 0;
                  bottom: 0;
                  width: 4px;
                  background: #e9ecef;
                  left: 50px;
                }
                .timeline-item {
                  position: relative;
                  margin-bottom: 50px;
                  padding-left: 120px;
                }
                .timeline-badge {
                  position: absolute;
                  left: 34px;
                  top: 0;
                  width: 32px;
                  height: 32px;
                  border-radius: 50%;
                  color: white;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  font-size: 16px;
                  z-index: 2;
                  box-shadow: 0 0 0 4px #fff;
                }
                .timeline-panel {
                  background: white;
                  border: 1px solid #e9ecef;
                  border-radius: 8px;
                  padding: 25px;
                  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                }
                .timeline-heading h4 {
                  margin: 0 0 15px 0;
                  color: #37517e;
                  font-weight: 700;
                }
                .timeline-body ul {
                  margin: 0;
                  padding-left: 20px;
                }
                .timeline-body li {
                  margin-bottom: 8px;
                }
              `}</style>
            </div>
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section id="cta" className="call-to-action section dark-background">
        <div className="container">
          <div className="row" data-aos="zoom-in">
            <div className="col-xl-9 text-center text-xl-start">
              <h3>Need Immediate Criminal Defense?</h3>
              <p>Contact us now for urgent legal assistance. Available 24/7 for police station representation.</p>
            </div>
            <div className="col-xl-3 cta-btn-container text-center">
              <Link className="cta-btn align-middle" href="/contact">Get Legal Help Now</Link>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
