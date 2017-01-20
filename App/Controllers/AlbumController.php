<?php
namespace App\Controllers;

use App\Models\{
    Album, AlbumPhoto, AlbumUser, User
};
use DoctrineTest\InstantiatorTestAsset\ExceptionAsset;

class AlbumController extends PageController
{
    public function actionIndex($id)
    {
        $result = parent::actionIndex($id);
        $result['templateNames'] = [
            'head',
            'navbar',
            'leftcolumn',
            'middlecolumnalbum',
            'rightcolumn',
            'footer',
        ];
        $result['albumId'] = $id;
        return $result;
    }

    public function actionInsertPhoto($id)
    {
        $this->userId = User::checkLogged();
        if (!isset($_FILES['uploadPhoto']) || $_FILES['uploadPhoto']['error'] != 0) {
            header('Location: /album/view/' . $id);
        }
        $path = ROOT . '/public/photos/'; // директория для загрузки
        $ext = explode('.',$_FILES['uploadPhoto']['name']); // расширение
        $newName = md5(time() . $ext[0]) . '.' . $ext[1]; // новое имя с расширением
        $full_path = $path . $newName; // полный путь с новым именем и расширением

        if(!move_uploaded_file($_FILES['uploadPhoto']['tmp_name'], $full_path)){
            throw new \Exception('Can\'t save file');
        }
        $newPhoto = new AlbumPhoto();
        $newPhoto->fileName = $newName;
        $newPhoto->albumId = $id;
        $newPhoto->status = 'active';
        $newPhoto->insert();
        header('Location: /album/' . $id);
    }

    public function actionDeletePhoto($id)
    {
        AlbumPhoto::delete($_GET['photoId']);
        header('Location: /album/view/' . $id);
    }

    public function actionUpdatePhoto($id)
    {
        AlbumPhoto::delete($_GET['photoId']);
        header('Location: /album/view/' . $id);
    }
    
    public function actionUpdateAlbum($id)
    {
        $this->userId = User::checkLogged();

        /*AlbumUser::join('albumId', 'App\Models\Album', 'id');
        Album::join('id', 'App\Models\AlbumPhoto', 'albumId', " AND status='active'");*/

        $userAlbum = Album::getByID($id);
        $userAlbum->name = $_POST['newAlbumName'];
        $userAlbum->update();
        
        header('Location: /album/' . $id);
    }
}
