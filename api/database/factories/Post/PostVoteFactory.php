<?php

namespace Database\Factories\Post;

use App\Models\Post\PostVote;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostVoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostVote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'user_id' => 0,
            'post_id' => 0,
            'upvote' => 0
        ];
    }
}
