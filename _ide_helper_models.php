<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Book
 *
 * @property int $id
 * @property string $title
 * @property string|null $isbn_10
 * @property string|null $isbn_13
 * @property string|null $publish_date
 * @property int|null $pages
 * @property string $cover_url
 * @property int|null $series_id
 * @property float|null $volume_number
 * @property int $oneshot
 * @property int $new
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $format
 * @property-read \App\Models\Report|null $reports
 * @property-read \App\Models\Series|null $series
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCoverUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereIsbn10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereIsbn13($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereOneshot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereVolumeNumber($value)
 */
	class Book extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BookKind
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BookKind newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookKind newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookKind query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookKind whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookKind whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookKind whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookKind whereUpdatedAt($value)
 */
	class BookKind extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BookVendor
 *
 * @property int $id
 * @property string $name
 * @property int $public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $path_to_logo
 * @property int|null $user_id
 * @property-read \App\Models\Report|null $reports
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor newQuery()
 * @method static \Illuminate\Database\Query\Builder|BookVendor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor wherePathToLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookVendor whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|BookVendor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BookVendor withoutTrashed()
 */
	class BookVendor extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Collection
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property int $total_books
 * @property float $total_cost
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Item[] $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Query\Builder|Collection onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTotalBooks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Collection withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Collection withoutTrashed()
 */
	class Collection extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DailyStatsSnapshot
 *
 * @property int $id
 * @property int $new_series
 * @property int $new_books
 * @property int $new_reports
 * @property int|null $new_users
 * @property int|null $new_collections
 * @property int|null $new_collection_items
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot query()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereNewBooks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereNewCollectionItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereNewCollections($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereNewReports($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereNewSeries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereNewUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyStatsSnapshot whereUpdatedAt($value)
 */
	class DailyStatsSnapshot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Item
 *
 * @property int $id
 * @property int $book_id
 * @property int $collection_id
 * @property int $vendor_id
 * @property float $price
 * @property string $bought_on
 * @property int $arrived
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Book|null $book
 * @property-read \App\Models\Collection|null $collection
 * @property-read \App\Models\BookVendor|null $vendor
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Query\Builder|Item onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereArrived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereBoughtOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereVendorId($value)
 * @method static \Illuminate\Database\Query\Builder|Item withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Item withoutTrashed()
 */
	class Item extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Report
 *
 * @property int $id
 * @property string $title
 * @property string|null $details
 * @property int $item_id
 * @property string $item_type
 * @property int $completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $assignee_id
 * @property-read \App\Models\User|null $assignee
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $item
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Query\Builder|Report onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereAssigneeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Report withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Report withoutTrashed()
 */
	class Report extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $short_name
 * @property string $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Series
 *
 * @property int $id
 * @property string $title
 * @property string $language
 * @property string $cover_url
 * @property string|null $publisher
 * @property string|null $summary
 * @property mixed|null $authors
 * @property mixed|null $contributions
 * @property int $new
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $kind
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @property-read int|null $books_count
 * @property-read \App\Models\Report|null $reports
 * @method static \Illuminate\Database\Eloquent\Builder|Series newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series query()
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereAuthors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereContributions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereCoverUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereUpdatedAt($value)
 */
	class Series extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SocialBadges
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|SocialBadges newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialBadges newQuery()
 * @method static \Illuminate\Database\Query\Builder|SocialBadges onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialBadges query()
 * @method static \Illuminate\Database\Query\Builder|SocialBadges withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SocialBadges withoutTrashed()
 */
	class SocialBadges extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $role_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SocialBadges[] $badges
 * @property-read int|null $badges_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Collection[] $collections
 * @property-read int|null $collections_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Report[] $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\Role|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Report[] $unfinishedReports
 * @property-read int|null $unfinished_reports_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

