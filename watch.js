const urlParams = new URLSearchParams(window.location.search);
const videoId = urlParams.get("id");

fetch("videos.json")
  .then(response => response.json())
  .then(videos => {
    const video = videos.find(v => v.id === videoId);

    if (!video) {
      document.getElementById("title").innerText = "Video not found";
      return;
    }

    document.getElementById("title").innerText = video.title;
    document.getElementById("description").innerText = video.description;
    document.getElementById("channel").innerText = video.channel;
    document.getElementById("views").innerText = video.views + " views";
    document.getElementById("uploaded").innerText = video.uploaded;

    document.getElementById("videoPlayer").src = video.file;
  });
