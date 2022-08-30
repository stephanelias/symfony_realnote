<?php

namespace App\Service ;

use App\Entity\Album;
use App\Entity\Artist;
use App\Repository\ArtistRepository;

class NoteService {

    public function titleToRepo(Album $album, ArtistRepository $artistRepository) {
        $titles = $album->getTitles() ;
        for ( $i = 0 ; $i < $titles->count() ; $i++) {
            //$title = $titles->get($i) ;
            if (str_contains($titles->get($i)->getName(),"(feat.")) {
                $a = explode("(feat.",$titles->get($i)->getName()) ;
                $f = substr($a[1],1,-1);
                if (str_contains($f,",")) {
                    $f1 = str_replace(" ","",$f) ;
                    $f2 = explode(",",$f1) ;
                    for($j = 0 ; $j < count($f2) ; $j++) {
                       $res = $artistRepository->findOneBy([
                           'name' => $f2[$j]
                       ]) ;
                       if ( $res === null ) {
                           $artist = new Artist() ;
                           $artist->setName($f2[$j]);
                           $titles->get($i)->addFeat($artist) ;
                           $artistRepository->add($artist,true);
                       }
                    }
                }
                else {
                    $res = $artistRepository->findOneBy([
                        'name' => $f
                    ]) ;
                    if ( $res === null ) {
                        $artist = new Artist() ;
                        $artist->setName($f) ;
                        $titles->get($i)->addFeat($artist) ;
                        $artistRepository->add($artist,true);
                    }
                }
            }
        }
    }
}