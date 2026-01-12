"use client";

import Link from "next/link";

export default function Probate() {
  return (
    <>
      {/* Hero Section */}
      <section id="hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>Grant of Probate & Estate Administration</h1>
              <p>Professional estate administration services under Malaysian law</p>
            </div>
          </div>
        </div>
      </section>

      {/* Introduction Section */}
      <section id="intro" className="about section">
        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
              <h3>Estate Administration</h3>
              <p className="fst-italic">
                Compassionate and professional services for the administration of deceased estates in Malaysia.
              </p>
              <ul>
                <li><i className="bi bi-check-circle"></i> <span>High Court and Land Office procedures</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Sensitive handling during difficult times</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Complete estate administration</span></li>
              </ul>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <h3>Governing Legislation</h3>
              <ul>
                <li><i className="bi bi-book"></i> <span><strong>Probate & Administration Act 1959</strong></span></li>
                <li><i className="bi bi-book"></i> <span><strong>Distribution Act 1958</strong></span></li>
                <li><i className="bi bi-book"></i> <span><strong>Wills Act 1959</strong></span></li>
                <li><i className="bi bi-book"></i> <span><strong>Small Estates Act 1955</strong></span></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Comparison Table Section */}
      <section id="comparison" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>Types of Estate Administration</h2>
          <p>Choose the right process for your situation</p>
        </div>

        <div className="container">
          <div className="row justify-content-center">
            <div className="col-lg-11" data-aos="fade-up" data-aos-delay="100">
              <div className="table-responsive">
                <table className="table table-bordered bg-white shadow-sm">
                  <thead className="table-dark">
                    <tr>
                      <th style={{width: "20%"}}>Type</th>
                      <th style={{width: "20%"}}>When to Use</th>
                      <th style={{width: "20%"}}>Authority</th>
                      <th style={{width: "20%"}}>Timeline</th>
                      <th style={{width: "20%"}}>Best For</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr className="table-primary">
                      <td><strong><i className="bi bi-file-earmark-text me-2"></i>Grant of Probate</strong></td>
                      <td>Valid will with executor</td>
                      <td>High Court (S.27 PAA)</td>
                      <td>6-12 months</td>
                      <td>Large estates with will</td>
                    </tr>
                    <tr className="table-info">
                      <td><strong><i className="bi bi-clipboard-check me-2"></i>Letters of Administration</strong></td>
                      <td>No will (intestate)</td>
                      <td>High Court (S.28 PAA)</td>
                      <td>6-12 months</td>
                      <td>Estates without will</td>
                    </tr>
                    <tr className="table-warning">
                      <td><strong><i className="bi bi-file-text me-2"></i>LA with Will Annexed</strong></td>
                      <td>Will exists but no executor</td>
                      <td>High Court</td>
                      <td>6-12 months</td>
                      <td>Will without executor</td>
                    </tr>
                    <tr className="table-success">
                      <td><strong><i className="bi bi-house-check me-2"></i>Small Estates</strong></td>
                      <td>Estate below RM2 million</td>
                      <td>Land Office (S.8 SEDA)</td>
                      <td>3-6 months</td>
                      <td>Smaller, simpler estates</td>
                    </tr>
                    <tr className="table-secondary">
                      <td><strong><i className="bi bi-moon-stars me-2"></i>Muslim Estates</strong></td>
                      <td>Deceased is Muslim</td>
                      <td>Syariah Court</td>
                      <td>Varies</td>
                      <td>Islamic inheritance (faraid)</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Flowchart Process */}
      <section id="flowchart" className="about section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Estate Administration Flowchart</h2>
          <p>Visual guide through the process</p>
        </div>

        <div className="container">
          <div className="row justify-content-center">
            <div className="col-lg-10" data-aos="fade-up" data-aos-delay="100">
              <div className="flowchart">
                <div className="flow-step start">
                  <div className="flow-box bg-dark text-white">
                    <i className="bi bi-flag-fill mb-2" style={{fontSize: "32px"}}></i>
                    <h5>Deceased Person</h5>
                    <p className="mb-0 small">Estate needs administration</p>
                  </div>
                  <div className="flow-arrow"><i className="bi bi-arrow-down"></i></div>
                </div>

                <div className="flow-step decision">
                  <div className="flow-box bg-warning">
                    <i className="bi bi-question-circle-fill mb-2" style={{fontSize: "32px"}}></i>
                    <h5>Is there a valid will?</h5>
                  </div>
                  <div className="flow-split">
                    <div className="flow-branch left">
                      <div className="flow-arrow"><i className="bi bi-arrow-down-left"></i></div>
                      <span className="badge bg-success">YES</span>
                      <div className="flow-box bg-success bg-opacity-25">
                        <h6>With Will (Testate)</h6>
                        <p className="small mb-0">Proceed to Grant of Probate</p>
                      </div>
                    </div>
                    <div className="flow-branch right">
                      <div className="flow-arrow"><i className="bi bi-arrow-down-right"></i></div>
                      <span className="badge bg-danger">NO</span>
                      <div className="flow-box bg-danger bg-opacity-25">
                        <h6>Without Will (Intestate)</h6>
                        <p className="small mb-0">Proceed to Letters of Administration</p>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="flow-step mt-5">
                  <div className="flow-box bg-primary bg-opacity-25">
                    <i className="bi bi-search mb-2" style={{fontSize: "32px", color: "#0d6efd"}}></i>
                    <h5>Asset Verification & Searches</h5>
                    <p className="mb-0 small">Properties, bank accounts, shares, EPF, insurance</p>
                  </div>
                  <div className="flow-arrow"><i className="bi bi-arrow-down"></i></div>
                </div>

                <div className="flow-step">
                  <div className="flow-box bg-info bg-opacity-25">
                    <i className="bi bi-file-earmark-check mb-2" style={{fontSize: "32px", color: "#0dcaf0"}}></i>
                    <h5>File Application</h5>
                    <p className="mb-0 small">High Court or Land Office (Small Estates)</p>
                  </div>
                  <div className="flow-arrow"><i className="bi bi-arrow-down"></i></div>
                </div>

                <div className="flow-step">
                  <div className="flow-box bg-secondary bg-opacity-25">
                    <i className="bi bi-bank mb-2" style={{fontSize: "32px", color: "#6c757d"}}></i>
                    <h5>Court Hearing & Grant Extraction</h5>
                    <p className="mb-0 small">Obtain Grant of Probate or Letters of Administration</p>
                  </div>
                  <div className="flow-arrow"><i className="bi bi-arrow-down"></i></div>
                </div>

                <div className="flow-step">
                  <div className="flow-box bg-success bg-opacity-25">
                    <i className="bi bi-cash-stack mb-2" style={{fontSize: "32px", color: "#198754"}}></i>
                    <h5>Collect Assets & Pay Debts</h5>
                    <p className="mb-0 small">Realize assets, settle liabilities</p>
                  </div>
                  <div className="flow-arrow"><i className="bi bi-arrow-down"></i></div>
                </div>

                <div className="flow-step end">
                  <div className="flow-box bg-primary text-white">
                    <i className="bi bi-check-circle-fill mb-2" style={{fontSize: "32px"}}></i>
                    <h5>Distribute to Beneficiaries</h5>
                    <p className="mb-0 small">Final distribution & accounts</p>
                  </div>
                </div>
              </div>

              <style jsx>{`
                .flowchart {
                  padding: 30px 0;
                }
                .flow-step {
                  text-align: center;
                  margin-bottom: 20px;
                }
                .flow-box {
                  display: inline-block;
                  padding: 25px 40px;
                  border-radius: 12px;
                  border: 2px solid rgba(0,0,0,0.1);
                  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                  min-width: 300px;
                }
                .flow-box h5 {
                  margin: 10px 0 5px 0;
                  font-weight: 700;
                }
                .flow-arrow {
                  font-size: 32px;
                  color: #6c757d;
                  margin: 10px 0;
                }
                .flow-split {
                  display: flex;
                  justify-content: center;
                  gap: 80px;
                  margin-top: 20px;
                }
                .flow-branch {
                  text-align: center;
                }
                .flow-branch .badge {
                  margin: 10px 0;
                  font-size: 14px;
                }
              `}</style>
            </div>
          </div>
        </div>
      </section>

      {/* Distribution Rules - Side by Side */}
      <section id="distribution" className="services section light-background">
        <div className="container section-title" data-aos="fade-up">
          <h2>How Estates Are Distributed</h2>
        </div>

        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div className="card border-success border-2 h-100">
                <div className="card-header bg-success text-white">
                  <h4 className="mb-0"><i className="bi bi-file-earmark-check me-2"></i>With Valid Will (Testate)</h4>
                </div>
                <div className="card-body">
                  <p className="lead">Estate distributed according to will</p>
                  <h6 className="mt-3">After deducting:</h6>
                  <ul>
                    <li>Funeral expenses</li>
                    <li>Debts and liabilities</li>
                    <li>Administration costs</li>
                  </ul>
                  <h6 className="mt-3">Then distribute to:</h6>
                  <p><strong>Beneficiaries named in will</strong></p>
                  <p className="text-muted small mb-0"><em>*Subject to family provision claims under Inheritance Family Provision Act 1971</em></p>
                </div>
              </div>
            </div>

            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div className="card border-danger border-2 h-100">
                <div className="card-header bg-danger text-white">
                  <h4 className="mb-0"><i className="bi bi-diagram-3 me-2"></i>Without Will (Intestate)</h4>
                </div>
                <div className="card-body">
                  <p className="lead">Distribution follows Distribution Act 1958</p>
                  <h6 className="mt-3">For Non-Muslims:</h6>
                  <ul>
                    <li>Spouse & children (fixed proportions)</li>
                    <li>Parents & siblings (if no spouse/children)</li>
                    <li>Order of priority by statute</li>
                  </ul>
                  <h6 className="mt-3">For Muslims:</h6>
                  <p><strong>Faraid distribution (Islamic inheritance law)</strong></p>
                  <p className="text-muted small mb-0"><em>*Determined by Syariah Court</em></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Timeline & Costs Cards */}
      <section id="timeline-costs" className="about section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Timeline & Costs</h2>
        </div>

        <div className="container">
          <div className="row gy-4 justify-content-center">
            <div className="col-lg-5" data-aos="zoom-in" data-aos-delay="100">
              <div className="card border-0 shadow-lg h-100">
                <div className="card-body text-center p-5">
                  <i className="bi bi-clock-history" style={{fontSize: "64px", color: "#47b2e4"}}></i>
                  <h3 className="mt-3 mb-4">Processing Time</h3>
                  <div className="timeline-info">
                    <h4 className="text-primary">High Court</h4>
                    <p className="lead mb-4">6-12 months</p>
                    <p className="text-muted">(Longer if contested)</p>
                  </div>
                  <hr className="my-4" />
                  <div className="timeline-info">
                    <h4 className="text-success">Small Estates</h4>
                    <p className="lead mb-0">3-6 months</p>
                  </div>
                </div>
              </div>
            </div>

            <div className="col-lg-5" data-aos="zoom-in" data-aos-delay="200">
              <div className="card border-0 shadow-lg h-100">
                <div className="card-body text-center p-5">
                  <i className="bi bi-currency-dollar" style={{fontSize: "64px", color: "#ffa76e"}}></i>
                  <h3 className="mt-3 mb-4">Legal Fees</h3>
                  <div className="fees-info">
                    <p className="lead">Based on estate value</p>
                    <h2 className="text-primary my-4">0.5% - 1.5%</h2>
                    <p>of gross estate value</p>
                  </div>
                  <hr className="my-4" />
                  <div className="fees-info">
                    <p className="text-muted mb-0"><strong>Plus:</strong> Court fees as prescribed by law</p>
                  </div>
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
              <h3>Need Estate Administration Assistance?</h3>
              <p>Let us guide you through the probate process with compassion and expertise</p>
            </div>
            <div className="col-xl-3 cta-btn-container text-center">
              <Link className="cta-btn align-middle" href="/contact">Contact Us Today</Link>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
