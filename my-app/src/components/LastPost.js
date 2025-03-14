import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

const LastPost = () => {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    const fetchPosts = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/post');
        const allPosts = response.data.data;
        console.log("Tất cả bài viết từ API:", allPosts);
    
        // Lọc các bài viết có status = 1 và type = 'post'
        const filteredPosts = allPosts.filter(post => 
          post.status === 1 && post.type.toString().trim() === 'post'
        );
        
        console.log("Bài viết đã lọc:", filteredPosts);
    
        // Sắp xếp các bài viết theo ngày tạo
        const sortedPosts = filteredPosts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    
        // Lấy 2 bài viết mới nhất
        const latestPosts = sortedPosts.slice(0, 2);
        setPosts(latestPosts);
      } catch (error) {
        console.error('Lỗi khi lấy dữ liệu bài viết:', error);
      }
    };

    fetchPosts();
  }, []);

  return (
    <section id="post-new" className="p-3">
      <h2 className="text-warning text-center">
        <i className="fas fa-newspaper"></i> BÀI VIẾT
      </h2>
      <div className="row">
        {posts.length > 0 ? (
          posts.map((post) => (
            <div key={post.id} className="col-md-6">
              <div className="card h-100" id="card-news1">
                <img
                  src={`http://localhost:8000/images/posts/${post.image}`} // Đường dẫn đến ảnh
                  alt={post.title}
                  className="card-img-top"
                  style={{ width: '100%' }} // Điều chỉnh kích thước ảnh
                />
                <div className="container">
                  <h4>
                    <Link
                      className="text-dark"
                      to={`/bai-viet/${post.id}`}
                      title={post.title}
                    >
                      {post.title}
                    </Link>
                  </h4>
                  <p>{post.description}</p>
                </div>
              </div>
            </div>
          ))
        ) : (
          <div className="col-12 text-center">
            <p>Không có bài viết nào.</p>
          </div>
        )}
      </div>
    </section>
  );
};

export default LastPost;
