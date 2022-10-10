<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 12; $i++) {
            $user = new User();
            $user->setEmail('email'.$i.'@mail.com');
            $user->setName("Имя номер " . $i);
            $user->setPassword($i*$i);
            if ($i % 4 == 0) {
                $user->setRoles(["ROLE_ADMIN"]);
            }
            else {
                $user->setRoles(["ROLE_USER"]);
            }
            $manager->persist($user);
            $post = new Post();
            $post->setText("Текст номер ". $i);
            $post->setAuthor($user);
            $post->setViews(0);
            $post->setAnnotation("Аннотация номер " . $i);
            $post->setName("Пост номер " . $i);
            if ($i < 9) {
                $post->setDate(new DateTime("2022-0" . ($i + 1) . "-0" . ($i + 1) . "T15:03:01.012345Z"));
            } else {
                $post->setDate(new DateTime("2022-" . ($i + 1) . "-" . ($i + 1) . "T15:03:01.012345Z"));
            }
            $manager->persist($post);
            $comment = new Comment();
            $comment->setText("Коммент номер ". $i);
            $comment->setAuthor($user);
            if ($i < 9) {
                $comment->setDate(new DateTime("2022-0" . ($i + 1) . "-0" . ($i + 1) . "T15:03:01.012345Z"));
            } else {
                $comment->setDate(new DateTime("2022-" . ($i + 1) . "-" . ($i + 1) . "T15:03:01.012345Z"));
            }
            $comment->setPost($post);
            $manager->persist($comment);


        }

        $manager->flush();
    }
}
