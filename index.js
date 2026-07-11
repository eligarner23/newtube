fetch("videos.json")
  .then(r => r.json())
  .then(videos => {
    const grid = document.getElementById("videoGrid");

    videos.forEach(video => {
      const card = document.createElement("div");
      card.className = "video-card";

      card.innerHTML = `
        <div class="thumbnail" style="background-image:url('${video.thumbnail}')"></div>
        <div class="video-title">${video.title}</div>
        <div class="video-meta">${video.channel} • ${video.views} views</div>
      `;

      card.onclick = () => {
        location.href = `watch.html?id=${video.id}`;
      };

      grid.appendChild(card);
    });
  });
