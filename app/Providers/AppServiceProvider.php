<?php

namespace App\Providers;

use App\Models\DetailBookType;
use App\Repositories\AssessmentRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\BookRepository;
use App\Repositories\CommentRepository;
use App\Repositories\DetailAuthorBookRepository;
use App\Repositories\DetailAuthorTypeRepository;
use App\Repositories\DetailBookTypeRepository;
use App\Repositories\DetailGroupUserRepository;
use App\Repositories\DetailPostBookRepository;
use App\Repositories\FollowRepository;
use App\Repositories\GroupRepository;
use App\Repositories\Interfaces\AssessmentInterface;
use App\Repositories\Interfaces\AuthorInterface;
use App\Repositories\Interfaces\BookInterface;
use App\Repositories\Interfaces\CommentInterface;
use App\Repositories\Interfaces\DetailAuthorBookInterface;
use App\Repositories\Interfaces\DetailAuthorTypeInterface;
use App\Repositories\Interfaces\DetailBookTypeInterface;
use App\Repositories\Interfaces\DetailGroupUserInterface;
use App\Repositories\Interfaces\DetailPostBookInterface;
use App\Repositories\Interfaces\FollowInterface;
use App\Repositories\Interfaces\GroupInterface;
use App\Repositories\Interfaces\LikeInterface;
use App\Repositories\Interfaces\NotificationInterface;
use App\Repositories\Interfaces\PostInterface;
use App\Repositories\Interfaces\ShareInterface;
use App\Repositories\Interfaces\TypeInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Interfaces\CloudInterface;
use App\Repositories\LikeRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PostRepository;
use App\Repositories\ShareRepository;
use App\Repositories\TypeRepository;
use App\Repositories\UserRepository;
use App\Repositories\CloudRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AssessmentInterface::class, AssessmentRepository::class);
        $this->app->bind(AuthorInterface::class, concrete: AuthorRepository::class);
        $this->app->bind(BookInterface::class, concrete: BookRepository::class);
        $this->app->bind(CommentInterface::class, CommentRepository::class);
        $this->app->bind(DetailAuthorBookInterface::class, DetailAuthorBookRepository::class);
        $this->app->bind(DetailAuthorTypeInterface::class, DetailAuthorTypeRepository::class);
        $this->app->bind(DetailBookTypeInterface::class, DetailBookTypeRepository::class);
        $this->app->bind(DetailGroupUserInterface::class, DetailGroupUserRepository::class);
        $this->app->bind(DetailPostBookInterface::class, DetailPostBookRepository::class);
        $this->app->bind(FollowInterface::class, FollowRepository::class);
        $this->app->bind(GroupInterface::class, GroupRepository::class);
        $this->app->bind(LikeInterface::class, LikeRepository::class);
        $this->app->bind(NotificationInterface::class, NotificationRepository::class);
        $this->app->bind(PostInterface::class, PostRepository::class);
        $this->app->bind(ShareInterface::class, ShareRepository::class);
        $this->app->bind(TypeInterface::class, TypeRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(ShareInterface::class, concrete: ShareRepository::class);
        $this->app->bind(CloudInterface::class, concrete: CloudRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
