<?php

namespace Database\Seeders;

use Database\Seeders\Article\ArticleLikeSeeder;
use Database\Seeders\Article\ArticleSeeder;
use Database\Seeders\Post\PostComment\PostCommentSeeder;
use Database\Seeders\Post\PostSeeder;
use Database\Seeders\Post\PostVoteSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') != 'production') {
            $this->call([
                UserProfileSeeder::class,
                RoleSeeder::class,
                CategorySeeder::class,
                LanguageSeeder::class,

                ArticleSeeder::class,
                ArticleLikeSeeder::class,

                PostSeeder::class,
                PostVoteSeeder::class,
                PostCommentSeeder::class
            ]);
        }
    }
}
