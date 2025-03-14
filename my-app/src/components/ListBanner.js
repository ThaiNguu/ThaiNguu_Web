import React, { useState, useEffect } from 'react';
import axios from 'axios';
import '../css/listbanner.css'; 

function BannerList() {
  const [banners, setBanners] = useState([]);
  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    axios.get('http://localhost:8000/api/banner')
      .then(response => {
        const activeBanners = response.data.data.filter(banner => banner.status === 1);
        setBanners(activeBanners);
      })
      .catch(error => {
        console.error('Có lỗi xảy ra khi gọi API:', error);
      });
  }, []);
  const nextSlide = () => {
    setCurrentIndex((prevIndex) => (prevIndex + 1) % banners.length);
  };
  const prevSlide = () => {
    setCurrentIndex((prevIndex) => (prevIndex - 1 + banners.length) % banners.length);
  };


  useEffect(() => {
    const interval = setInterval(nextSlide, 5000);
    return () => clearInterval(interval);
  }, [banners.length]);
  const goToSlide = (index) => {
    setCurrentIndex(index);
  };

  return (
    <section id="custom-slider" className="p-3" style={{ paddingTop: '100px' }}>
      <div className="slider-container">
        <button className="prev" onClick={prevSlide}>❮</button>
        <div
          className="slides"
          style={{
            transform: `translateX(${-currentIndex * 100}%)`,
          }}
        >
          {banners.map((banner) => (
            <div className="slide" key={banner.id}>
              <img
                src={`http://localhost:8000/images/banners/${banner.image}`}
                alt={banner.name}
                className="d-block w-100"
              />
            </div>
          ))}
        </div>
        <button className="next" onClick={nextSlide}>❯</button>

        <div className="dots">
          {banners.map((_, index) => (
            <button
              key={index}
              className={`dot ${currentIndex === index ? 'active' : ''}`}
              onClick={() => goToSlide(index)}
            ></button>
          ))}
        </div>
      </div>
    </section>
  );
}

export default BannerList;
