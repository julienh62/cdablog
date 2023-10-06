
    // const bouton = document.querySelector("#loadPosts");
  const posts = document.querySelector("#posts");
  let offset = 5;
  let lock = false;
  
  async function loadPosts() {
      const response = await fetch("api/" + offset);
      const html = await response.text();
      offset += 5;
      lock = false;
      posts.innerHTML += html;
  }
  
  // btnLoadPosts.addEventListener("click", loadPosts);
  window.addEventListener("scroll", function () {
      console.log("scroll");
      if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - window.innerHeight / 3) && !lock) {
          lock = true;
          loadPosts();
      }
  });
 