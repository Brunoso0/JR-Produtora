document.addEventListener('DOMContentLoaded', function() {
    fetch('/live_videos')
        .then(response => response.json())
        .then(data => {
            const upcomingVideosContainer = document.getElementById('upcomingVideos');
            const latestVideosContainer = document.getElementById('latestVideos');

            const upcomingVideos = data.upcoming;
            const latestVideos = data.latest;

            upcomingVideos.forEach(video => {
                const videoLink = document.createElement('a');
                videoLink.href = `https://www.youtube.com/watch?v=${video.id}`;
                videoLink.textContent = video.title;
                upcomingVideosContainer.appendChild(videoLink);
                upcomingVideosContainer.appendChild(document.createElement('br'));
            });

            latestVideos.forEach(video => {
                const videoLink = document.createElement('a');
                videoLink.href = `https://www.youtube.com/watch?v=${video.id}`;
                videoLink.textContent = video.title;
                latestVideosContainer.appendChild(videoLink);
                latestVideosContainer.appendChild(document.createElement('br'));
            });
        })
        .catch(error => console.error('Erro ao obter vídeos ao vivo:', error));
});