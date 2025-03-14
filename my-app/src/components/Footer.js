// src/components/Footer.js
import React from "react";

const Footer = ({ listMenu }) => {
  return (
    <footer id="footer-container" className="footer bg-dark text-light p-4">
      <div className="container">
        <div className="row">
          {/* Shop Information */}
          <div className="col-md-4 mb-4">
            <h5>About Our Shop</h5>
            <p>
              We are a leading fashion store offering a wide range of stylish
              clothing and accessories. Our mission is to provide quality
              fashion at affordable prices.
            </p>
            <p>
              © Bản quyền thuộc về{" "}
              <a href="/" className="text-light">
                Thái Ngưu
              </a>{" "}
              All rights reserved
            </p>
          </div>

          {/* Footer Links */}
          <div className="col-md-4 mb-4">
            <h5>Quick Links</h5>
            <ul className="list-unstyled">
              <li>
                <a href="/shop" className="text-light">
                  Shop
                </a>
              </li>
              <li>
                <a href="/about" className="text-light">
                  About Us
                </a>
              </li>
              <li>
                <a href="/contact" className="text-light">
                  Contact
                </a>
              </li>
              <li>
                <a href="/faq" className="text-light">
                  FAQ
                </a>
              </li>
              <li>
                <a href="/returns" className="text-light">
                  Returns & Exchanges
                </a>
              </li>
            </ul>
          </div>

          {/* Contact & Social Media */}
          <div className="col-md-4 mb-4">
            <h5>Contact Us</h5>
            <p>
              <i className="fas fa-phone-alt"></i> +84 123 456 789
            </p>
            <p>
              <i className="fas fa-envelope"></i> support@shop.com
            </p>
            <div className="social-icons">
              <a href="https://facebook.com" className="text-light mr-3">
                <i className="fab fa-facebook"></i>
              </a>
              <a href="https://instagram.com" className="text-light mr-3">
                <i className="fab fa-instagram"></i>
              </a>
              <a href="https://twitter.com" className="text-light">
                <i className="fab fa-twitter"></i>
              </a>
            </div>
          </div>
        </div>

        {/* Newsletter Subscription */}
        <div className="row mt-4">
          <div className="col-md-6">
            <h5>Subscribe to our Newsletter</h5>
            <form className="newsletter-form">
              <div className="input-group">
                <input
                  type="email"
                  className="form-control"
                  placeholder="Enter your email"
                />
                <div className="input-group-append">
                  <button className="btn btn-primary" type="submit">
                    Subscribe
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div className="col-md-6 text-right">
            <img
              src="https://dangkywebvoibocongthuong.com/wp-content/uploads/2021/11/logo-da-thong-bao-bo-cong-thuong.png"
              alt="Footer Image"
              className="img-fluid"
              style={{ width: "150px" }}
            />
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
