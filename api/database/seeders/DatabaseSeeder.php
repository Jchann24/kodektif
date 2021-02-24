<?php

namespace Database\Seeders;

use Database\Seeders\Article\ArticleLikeSeeder;
use Database\Seeders\Article\ArticleSeeder;
use Database\Seeders\Post\PostComment\PostCommentReplySeeder;
use Database\Seeders\Post\PostComment\PostCommentSeeder;
use Database\Seeders\Post\PostComment\PostCommentVoteSeeder;
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
            $start = microtime(true);

            $this->call([
                UserProfileSeeder::class,
                RoleSeeder::class,
                CategorySeeder::class,
                LanguageSeeder::class,

                ArticleSeeder::class,
                ArticleLikeSeeder::class,

                PostSeeder::class,
                PostVoteSeeder::class,
                PostCommentSeeder::class,
                PostCommentVoteSeeder::class,
                PostCommentReplySeeder::class
            ]);
            $end = microtime(true);
            $time = number_format(($end - $start) * 1000, 2);
            $info = 'Seeded database in ' . $time . ' ms';
            $this->command->info($info);
        }
    }
}
