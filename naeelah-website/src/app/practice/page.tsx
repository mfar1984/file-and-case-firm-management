import Link from "next/link";

export default function Practice() {
  const practices = [
    {
      title: "Civil Litigation",
      description: "Expert representation in civil disputes, contract matters, and commercial litigation.",
      link: "/practice/civil-litigation",
    },
    {
      title: "Criminal Law",
      description: "Comprehensive criminal defense services with experienced legal representation.",
      link: "/practice/criminal-law",
    },
    {
      title: "Conveyancing",
      description: "Property transaction services including sales, purchases, and transfers.",
      link: "/practice/conveyancing",
    },
    {
      title: "Grant of Probate",
      description: "Estate administration and probate matters handled with care and professionalism.",
      link: "/practice/probate",
    },
  ];

  return (
    <>
      <section id="practice-hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>Our Practice Areas</h1>
              <p>Comprehensive legal solutions tailored to your needs</p>
            </div>
          </div>
        </div>
      </section>

      <section id="services" className="services section">
        <div className="container">
          <div className="row gy-4">
            {practices.map((practice, index) => (
              <div key={index} className="col-lg-6" data-aos="fade-up" data-aos-delay={index * 100}>
                <div className="service-item position-relative">
                  <h3>{practice.title}</h3>
                  <p>{practice.description}</p>
                  <Link href={practice.link} className="read-more stretched-link">
                    Learn More <i className="bi bi-arrow-right"></i>
                  </Link>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </>
  );
}

