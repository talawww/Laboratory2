<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
   body {
         font-family: Arial, sans-serif;
         text-align: center;
         background-color: #f5f5f5;
         padding: 20px;
     }

     h1 {
         color: #333;
     }

     #player-container {
         max-width: 400px;
         margin: 0 auto;
         padding: 20px;
         background-color: #fff;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
     }

     audio {
         width: 100%;
     }

     #playlist {
         list-style: none;
         padding: 0;
     }

     #playlist li {
         cursor: pointer;
         padding: 10px;
         background-color: #eee;
         margin: 5px 0;
         transition: background-color 0.2s ease-in-out;
     }

     #playlist li:hover {
         background-color: #C5C6C5;
     }

     #playlist li.active {
         background-color: #007bff;
         color: #fff;
     }
    </style>
</head>
<body>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Playlists</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <br>
          <?php if(isset($plays)): ?>
              <?php foreach($plays as $p): ?>
                  <a href="/selectedplaylist/<?= $p['playlistname']; ?>" class="btn btn-primary" style="text-decoration: none;"><?= $p['playlistname']; ?></a>
                  <a href="/deleteplaylist/<?= $p['id']; ?>" class="hover-effect">
                      <img src="<?= base_url(); ?>/delete.png">
                  </a>
              <br><br>
              <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="modal-footer">
          <a href="#" data-bs-dismiss="modal" class="btn btn-secondary"style="text-decoration: none;">Close</a>
          <a href="#" data-bs-toggle="modal" data-bs-target="#createPlaylistModal" class="btn btn-primary"style="text-decoration: none;">Create New Playlist</a>
        </div>
      </div>
    </div>
  </div>
<!-- Modal for playlist creation form -->
<div class="modal fade" id="createPlaylistModal" tabindex="-1" aria-labelledby="createPlaylistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPlaylistModalLabel">Create New Playlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <!-- Add your form here -->
               <form action="/createplaylist" method="post">
                    <div class="mb-3">
                        <label for="playlistName" class="form-label">Playlist Name</label>
                        <input type="text" class="form-control" id="playlistName" name="playlistName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

  <form action="/search" method="get">
    <input type="search" name="search" placeholder="search a song">
    <button type="submit" class="btn btn-primary">Search</button>
  </form><br>

    <h1>Music Player</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  My Playlist
</button><br></br>

<!--INPUT FILE -->
<h3>Upload a Song:</h3>
<form action="/addsong" method="post" enctype="multipart/form-data">
  <label for="myfile">Select file:</label>
  <input type="file" id="myfile" name="myfile" accept=".mp3"> <input type="submit" value="Upload">
</form>
<br></br>

<audio id="audio" controls autoplay></audio>
    <ul id="playlist">
  <?php if ($mus): ?>
      <?php foreach ($mus as $music):?>
          <li data-src="<?=base_url(); ?>/music/<?= $music['musicname']; ?>.mp3"><?= $music['musicname']; ?>
           <a href="/addtopplaylist" class ="hover-effect">
            <img src="<?=base_url(); ?>/add.png">
      </a>
    </li>
       <?php endforeach; ?>
  <?php else:?>
     <?php foreach ($music as $m):?>
          <li data-src="<?=base_url(); ?>/music/<?= $m['musicname']; ?>.mp3"><?= $m['musicname']; ?>
           <a href="/addtopplaylist" class ="hover-effect">
            <img src="<?=base_url(); ?>/add.png">
      </a>
    </li>
   <?php endforeach; ?>
   <?php endif; ?>
   <ul>
    <div class="modal" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Select from playlist</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
          <form action="/" method="post">
            <!-- <p id="modalData"></p> -->
            <input type="hidden" id="musicID" name="musicID">
            <select  name="playlist" class="form-control" >
              
              <option value="playlist">playlist</option>

            </select>
            <input type="submit" name="add">
            </form>
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>
    <script>
    $(document).ready(function () {
  // Get references to the button and modal
  const modal = $("#myModal");
  const modalData = $("#modalData");
  const musicID = $("#musicID");
  // Function to open the modal with the specified data
  function openModalWithData(dataId) {
    // Set the data inside the modal content
    modalData.text("Data ID: " + dataId);
    musicID.val(dataId);
    // Display the modal
    modal.css("display", "block");
  }

  // Add click event listeners to all open modal buttons

  // When the user clicks the close button or outside the modal, close it
  modal.click(function (event) {
    if (event.target === modal[0] || $(event.target).hasClass("close")) {
      modal.css("display", "none");
    }
  });
});
    </script>
    <script>
        const audio = document.getElementById('audio');
        const playlist = document.getElementById('playlist');
        const playlistItems = playlist.querySelectorAll('li');

        let currentTrack = 0;

        function playTrack(trackIndex) {
            if (trackIndex >= 0 && trackIndex < playlistItems.length) {
                const track = playlistItems[trackIndex];
                const trackSrc = track.getAttribute('data-src');
                audio.src = trackSrc;
                audio.play();
                currentTrack = trackIndex;
            }
        }

        function nextTrack() {
            currentTrack = (currentTrack + 1) % playlistItems.length;
            playTrack(currentTrack);
        }

        function previousTrack() {
            currentTrack = (currentTrack - 1 + playlistItems.length) % playlistItems.length;
            playTrack(currentTrack);
        }

        playlistItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                playTrack(index);
            });
        });

        audio.addEventListener('ended', () => {
            nextTrack();
        });

        playTrack(currentTrack);
    </script>
</body>
</html>
 