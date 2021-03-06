<!-- Navbar -->
<div class="w3-top">
    <ul class="w3-navbar w3-theme-d2 w3-left-align w3-large">
        <li class="w3-hide-medium w3-hide-large w3-opennav w3-right">
            <a class="w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
        </li>
        <li><a href="/" class="w3-padding-large w3-theme-d4"><i class="fa fa-home w3-margin-right">Main</i></a></li>
        <li class="w3-hide-small"><a href="#" class="w3-padding-large w3-hover-white" title="News"><i class="fa fa-globe">News</i></a></li>
        <li class="w3-hide-small"><a href="/profile/show/" class="w3-padding-large w3-hover-white" title="Account Settings"><i class="fa fa-user">Profile</i></a></li>
        <li class="w3-hide-small"><a href="/dialogs" class="w3-padding-large w3-hover-white" title="Messages"><i class="fa fa-envelope">Messages</i><span class="w3-badge w3-right w3-small w3-green"><?=$unreadMessagesNum?></span></a></li>
        <li class="w3-hide-small w3-dropdown-hover">
            <a href="#" class="w3-padding-large w3-hover-white" title="Notifications"><i class="fa fa-bell">Notifications</i><span class="w3-badge w3-right w3-small w3-green"><?=count($friendReqs)+$commentNewsNum+$commentPhotosNum+$commentAvatarNum?></span></a>
            <div class="w3-dropdown-content w3-white w3-card-4">
                <a href="/friend/incoming/"><?=count($friendReqs)?> new friend requests</a>
                <a href="#"><?=$commentNewsNum?> new comments to your news</a>
                <a href="#"><?=$commentPhotosNum?> new comments to your photos</a>
                <a href="#"><?=$commentAvatarNum?> new comments to your avatar</a>
            </div>
        </li>
        <li class="w3-hide-small"><a href="/friend/index" class="w3-padding-large w3-hover-white" title="Account Settings"><i class="fa fa-user">My friends</i></a></li>
        <li class="w3-hide-small w3-right"><a href="/user/logout/" class="w3-padding-large w3-hover-white" title="Logout"><img src="/avatars/<?=$user->avatarFileName?>" class="w3-circle" style="height:25px;width:25px" alt="Avatar"></a></li>
    </ul>
</div>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:51px">
    <ul class="w3-navbar w3-left-align w3-large w3-theme">
        <li><a class="w3-padding-large" href="#">Link 1</a></li>
        <li><a class="w3-padding-large" href="#">Link 2</a></li>
        <li><a class="w3-padding-large" href="#">Link 3</a></li>
        <li><a class="w3-padding-large" href="#">My Profile</a></li>
    </ul>
</div>

<script>
    // Used to toggle the menu on smaller screens when clicking on the menu button
    function openNav() {
        var x = document.getElementById("navDemo");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>
