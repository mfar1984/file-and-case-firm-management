"use client";

import { useEffect } from "react";

export default function StatsCounter() {
  useEffect(() => {
    // Initialize PureCounter when component mounts
    const initCounter = () => {
      if (typeof window !== 'undefined' && (window as any).PureCounter) {
        new (window as any).PureCounter();
      }
    };

    // Try to initialize immediately
    initCounter();

    // Also try after a short delay (in case script hasn't loaded yet)
    const timer = setTimeout(initCounter, 500);

    return () => clearTimeout(timer);
  }, []);

  return (
    <section id="stats-counter" className="stats-counter section">
      <div className="container" data-aos="fade-up" data-aos-delay="100">
        <div className="row gy-4">
          <div className="col-lg-3 col-md-6">
            <div className="stats-item d-flex align-items-center w-100 h-100">
              <i className="bi bi-people color-blue flex-shrink-0"></i>
              <div>
                <span 
                  data-purecounter-start="0" 
                  data-purecounter-end="500" 
                  data-purecounter-duration="1" 
                  className="purecounter"
                >
                  0
                </span>
                <p>Happy Clients</p>
              </div>
            </div>
          </div>

          <div className="col-lg-3 col-md-6">
            <div className="stats-item d-flex align-items-center w-100 h-100">
              <i className="bi bi-journal-richtext color-orange flex-shrink-0"></i>
              <div>
                <span 
                  data-purecounter-start="0" 
                  data-purecounter-end="1000" 
                  data-purecounter-duration="1" 
                  className="purecounter"
                >
                  0
                </span>
                <p>Cases Handled</p>
              </div>
            </div>
          </div>

          <div className="col-lg-3 col-md-6">
            <div className="stats-item d-flex align-items-center w-100 h-100">
              <i className="bi bi-clock color-green flex-shrink-0"></i>
              <div>
                <span 
                  data-purecounter-start="0" 
                  data-purecounter-end="15" 
                  data-purecounter-duration="1" 
                  className="purecounter"
                >
                  0
                </span>
                <p>Years of Experience</p>
              </div>
            </div>
          </div>

          <div className="col-lg-3 col-md-6">
            <div className="stats-item d-flex align-items-center w-100 h-100">
              <i className="bi bi-award color-pink flex-shrink-0"></i>
              <div>
                <span 
                  data-purecounter-start="0" 
                  data-purecounter-end="4" 
                  data-purecounter-duration="1" 
                  className="purecounter"
                >
                  0
                </span>
                <p>Practice Areas</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

