"use client";

import Link from "next/link";

export default function Conveyancing() {
  return (
    <>
      {/* Hero Section */}
      <section id="hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>Conveyancing</h1>
              <p>Professional property transaction services under Malaysian land law</p>
            </div>
          </div>
        </div>
      </section>

      {/* Introduction Section */}
      <section id="intro" className="about section">
        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
              <h3>Conveyancing Services</h3>
              <p className="fst-italic">
                Comprehensive legal services for all types of property transactions in Malaysia, ensuring compliance 
                with the National Land Code 1965.
              </p>
              <ul>
                <li><i className="bi bi-check-circle"></i> <span>Individual and strata title transactions</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Complete due diligence and searches</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Smooth property transfers</span></li>
              </ul>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <h3>Governing Legislation</h3>
              <ul>
                <li><i className="bi bi-book"></i> <span><strong>National Land Code 1965 (NLC)</strong> - Land law</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Strata Titles Act 1985</strong> - Strata properties</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Stamp Act 1949</strong> - Stamp duty</span></li>
                <li><i className="bi bi-book"></i> <span><strong>Housing Development Act 1966</strong> - New properties</span></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Services in Accordion Style */}
      <section id="services" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Our Conveyancing Services</h2>
          <p>Complete legal support for all property transactions</p>
        </div>

        <div className="container">
          <div className="row justify-content-center">
            <div className="col-lg-10" data-aos="fade-up" data-aos-delay="100">
              <div className="accordion" id="servicesAccordion">
                {/* Sale & Purchase */}
                <div className="accordion-item border-0 shadow-sm mb-3">
                  <h2 className="accordion-header">
                    <button className="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#service1">
                      <i className="bi bi-house-door me-3" style={{fontSize: "24px", color: "#47b2e4"}}></i>
                      <strong>Sale & Purchase</strong>
                    </button>
                  </h2>
                  <div id="service1" className="accordion-collapse collapse show" data-bs-parent="#servicesAccordion">
                    <div className="accordion-body">
                      <div className="row">
                        <div className="col-md-6">
                          <ul>
                            <li>Subsale transactions (secondary market)</li>
                            <li>New property from developers</li>
                            <li>Auction property purchases</li>
                          </ul>
                        </div>
                        <div className="col-md-6">
                          <ul>
                            <li>En-bloc sales</li>
                            <li>SPA review & signing</li>
                            <li>Completion of sale</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Loan Documentation */}
                <div className="accordion-item border-0 shadow-sm mb-3">
                  <h2 className="accordion-header">
                    <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service2">
                      <i className="bi bi-bank me-3" style={{fontSize: "24px", color: "#ffa76e"}}></i>
                      <strong>Loan Documentation</strong>
                    </button>
                  </h2>
                  <div id="service2" className="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div className="accordion-body">
                      <div className="row">
                        <div className="col-md-6">
                          <ul>
                            <li>Loan Agreement review</li>
                            <li>Charge registration (Form 16A NLC)</li>
                            <li>Loan redemption</li>
                          </ul>
                        </div>
                        <div className="col-md-6">
                          <ul>
                            <li>Refinancing</li>
                            <li>Islamic financing (Musharakah, Murabahah)</li>
                            <li>Bank documentation</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Transfer of Ownership */}
                <div className="accordion-item border-0 shadow-sm mb-3">
                  <h2 className="accordion-header">
                    <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service3">
                      <i className="bi bi-arrow-left-right me-3" style={{fontSize: "24px", color: "#6fc383"}}></i>
                      <strong>Transfer of Ownership</strong>
                    </button>
                  </h2>
                  <div id="service3" className="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div className="accordion-body">
                      <div className="row">
                        <div className="col-md-6">
                          <ul>
                            <li>Transfer (Form 14A NLC)</li>
                            <li>Gift (hibah) transfers</li>
                            <li>Inheritance transmission</li>
                          </ul>
                        </div>
                        <div className="col-md-6">
                          <ul>
                            <li>Court order transfers</li>
                            <li>Matrimonial property transfers</li>
                            <li>Land Office registration</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Searches */}
                <div className="accordion-item border-0 shadow-sm mb-3">
                  <h2 className="accordion-header">
                    <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service4">
                      <i className="bi bi-search me-3" style={{fontSize: "24px", color: "#ff5ca1"}}></i>
                      <strong>Searches & Due Diligence</strong>
                    </button>
                  </h2>
                  <div id="service4" className="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div className="accordion-body">
                      <div className="row">
                        <div className="col-md-6">
                          <ul>
                            <li>Land title searches</li>
                            <li>Bankruptcy searches (INSOL/MDI)</li>
                            <li>Litigation searches (SPEKS)</li>
                          </ul>
                        </div>
                        <div className="col-md-6">
                          <ul>
                            <li>Company searches (SSM)</li>
                            <li>Encumbrance verification</li>
                            <li>Title verification</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Step-by-Step Process */}
      <section id="process" className="about section">
        <div className="container section-title" data-aos="fade-up">
          <h2>The Conveyancing Process</h2>
          <p>A clear step-by-step guide through your property transaction</p>
        </div>

        <div className="container">
          <div className="row">
            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div className="process-card bg-primary bg-gradient text-white p-4 mb-4">
                <h4 className="text-white mb-3"><i className="bi bi-cart-check me-2"></i>For Buyers</h4>
                <div className="process-steps">
                  <div className="step-item mb-3">
                    <span className="step-number">1</span>
                    <strong>Review & Sign SPA</strong>
                    <p className="mb-0 small">We review the Sale & Purchase Agreement and explain all terms</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">2</span>
                    <strong>Pay Deposit</strong>
                    <p className="mb-0 small">Pay booking fee and deposit as per agreement</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">3</span>
                    <strong>Loan Application</strong>
                    <p className="mb-0 small">We assist with loan documentation and bank submissions</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">4</span>
                    <strong>Conduct Searches</strong>
                    <p className="mb-0 small">Title, bankruptcy, and litigation searches</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">5</span>
                    <strong>Stamp SPA</strong>
                    <p className="mb-0 small">Stamping at LHDN within 30 days</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">6</span>
                    <strong>Pay Balance</strong>
                    <p className="mb-0 small">Payment of balance purchase price</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">7</span>
                    <strong>Execute Transfer</strong>
                    <p className="mb-0 small">Sign transfer documents (Form 14A)</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">8</span>
                    <strong>Register at Land Office</strong>
                    <p className="mb-0 small">Submit for registration</p>
                  </div>
                  <div className="step-item">
                    <span className="step-number">9</span>
                    <strong>Receive Title Deed</strong>
                    <p className="mb-0 small">Collect registered title deed</p>
                  </div>
                </div>
              </div>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div className="process-card bg-success bg-gradient text-white p-4 mb-4">
                <h4 className="text-white mb-3"><i className="bi bi-cash-stack me-2"></i>For Sellers</h4>
                <div className="process-steps">
                  <div className="step-item mb-3">
                    <span className="step-number">1</span>
                    <strong>Provide Documents</strong>
                    <p className="mb-0 small">Property title and relevant documents</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">2</span>
                    <strong>Sign SPA</strong>
                    <p className="mb-0 small">Execute Sale & Purchase Agreement</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">3</span>
                    <strong>Redeem Loan</strong>
                    <p className="mb-0 small">Settle existing loan (if any)</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">4</span>
                    <strong>Sign Transfer</strong>
                    <p className="mb-0 small">Execute transfer documents</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">5</span>
                    <strong>Pay RPGT</strong>
                    <p className="mb-0 small">Real Property Gains Tax payment</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">6</span>
                    <strong>Obtain Charge Release</strong>
                    <p className="mb-0 small">Bank releases charge on title</p>
                  </div>
                  <div className="step-item mb-3">
                    <span className="step-number">7</span>
                    <strong>Collect Proceeds</strong>
                    <p className="mb-0 small">Receive sale proceeds</p>
                  </div>
                  <div className="step-item">
                    <span className="step-number">8</span>
                    <strong>Deliver Possession</strong>
                    <p className="mb-0 small">Hand over vacant possession</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <style jsx>{`
            .process-card {
              border-radius: 8px;
              box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            }
            .step-item {
              position: relative;
              padding-left: 50px;
            }
            .step-number {
              position: absolute;
              left: 0;
              top: 0;
              width: 35px;
              height: 35px;
              background: rgba(255,255,255,0.3);
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
              font-weight: bold;
              font-size: 16px;
            }
          `}</style>
        </div>
      </section>

      {/* Legal Fees Table */}
      <section id="fees" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Legal Fees</h2>
          <p>Transparent fees based on Solicitors' Remuneration Order 2017</p>
        </div>

        <div className="container">
          <div className="row justify-content-center">
            <div className="col-lg-8" data-aos="fade-up" data-aos-delay="100">
              <div className="card border-0 shadow">
                <div className="card-body p-4">
                  <h4 className="mb-4 text-center">Standard Legal Fees (Third Schedule)</h4>
                  <table className="table table-hover">
                    <thead className="table-primary">
                      <tr>
                        <th>Property Value</th>
                        <th className="text-end">Legal Fee Rate</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>First RM500,000</td>
                        <td className="text-end"><strong>1%</strong> (min RM500)</td>
                      </tr>
                      <tr>
                        <td>Next RM500,000</td>
                        <td className="text-end"><strong>0.8%</strong></td>
                      </tr>
                      <tr>
                        <td>Next RM2,000,000</td>
                        <td className="text-end"><strong>0.7%</strong></td>
                      </tr>
                      <tr>
                        <td>Next RM2,000,000</td>
                        <td className="text-end"><strong>0.6%</strong></td>
                      </tr>
                      <tr className="table-active">
                        <td>Above RM5,000,000</td>
                        <td className="text-end"><strong>0.5%</strong></td>
                      </tr>
                    </tbody>
                  </table>
                  <p className="text-muted text-center mt-3 mb-0"><em>*Separate fees apply for loan documentation</em></p>
                </div>
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
              <h3>Planning a Property Transaction?</h3>
              <p>Let us handle your conveyancing needs with professionalism and efficiency</p>
            </div>
            <div className="col-xl-3 cta-btn-container text-center">
              <Link className="cta-btn align-middle" href="/contact">Get Started Today</Link>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
