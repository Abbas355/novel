<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGenresTable extends Migration
{
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();

            // Regular indexes
            $table->index('name', 'genres_name_idx');
            $table->index('slug', 'genres_slug_idx');
        });

        // Add constraints
        DB::statement('ALTER TABLE genres ADD CONSTRAINT genres_name_length_check CHECK (length(name) >= 1 AND length(name) <= 255)');

        // Only add trigram indexes if extension exists
        if ($this->pgTrgmExtensionExists()) {
            DB::statement('CREATE INDEX genres_name_trgm_idx ON genres USING gin (name gin_trgm_ops)');
            DB::statement('CREATE INDEX genres_description_trgm_idx ON genres USING gin (description gin_trgm_ops)');
        }

        // Insert default genres
        $this->seedDefaultGenres();
    }

    protected function pgTrgmExtensionExists(): bool
    {
        try {
            $result = DB::selectOne("SELECT 1 FROM pg_extension WHERE extname = 'pg_trgm'");
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function seedDefaultGenres()
    {
        $genres = [
            ['name' => 'Action', 'slug' => 'action', 'description' => 'Fast-paced novels filled with excitement and adventure'],
            ['name' => 'Romance', 'slug' => 'romance', 'description' => 'Stories focusing on romantic relationships'],
            ['name' => 'Fantasy', 'slug' => 'fantasy', 'description' => 'Tales of magic, mythical creatures, and epic adventures'],
            ['name' => 'Science Fiction', 'slug' => 'sci-fi', 'description' => 'Stories exploring futuristic concepts and technology'],
            ['name' => 'Mystery', 'slug' => 'mystery', 'description' => 'Intriguing novels focused on solving crimes or puzzles'],
            ['name' => 'Horror', 'slug' => 'horror', 'description' => 'Scary stories designed to thrill and frighten'],
            ['name' => 'Historical', 'slug' => 'historical', 'description' => 'Stories set in the past with historical accuracy'],
            ['name' => 'Comedy', 'slug' => 'comedy', 'description' => 'Humorous tales meant to entertain and amuse'],
            ['name' => 'Drama', 'slug' => 'drama', 'description' => 'Character-driven stories with emotional depth'],
            ['name' => 'Thriller', 'slug' => 'thriller', 'description' => 'Suspenseful novels that keep readers on edge'],
            ['name' => 'Adventure', 'slug' => 'adventure', 'description' => 'Stories of exciting journeys and exploration'],
            ['name' => 'Contemporary', 'slug' => 'contemporary', 'description' => 'Modern-day stories reflecting current times'],
            ['name' => 'Urban Fantasy', 'slug' => 'urban-fantasy', 'description' => 'Fantasy stories set in modern urban settings'],
            ['name' => 'Young Adult', 'slug' => 'young-adult', 'description' => 'Stories targeting teenage and young adult readers'],
            ['name' => 'Literary', 'slug' => 'literary', 'description' => 'Character-focused stories with artistic merit'],
            ['name' => 'Harem', 'slug' => 'harem', 'description' => 'Stories featuring multiple romantic interests'],
            ['name' => 'Adult', 'slug' => 'adult', 'description' => 'Mature content intended for adult audiences'],
            ['name' => 'Cultivation', 'slug' => 'cultivation', 'description' => 'Stories about martial artists and spiritual cultivation'],
            ['name' => 'Game', 'slug' => 'game', 'description' => 'Stories based on or involving video games and gaming worlds'],
            ['name' => 'System', 'slug' => 'system', 'description' => 'Stories featuring system-based progression and mechanics'],
            ['name' => 'Reincarnation', 'slug' => 'reincarnation', 'description' => 'Stories about characters being reborn or transported to new worlds'],
            ['name' => 'Ecchi', 'slug' => 'ecchi', 'description' => 'Stories with mild adult themes and fanservice'],
            ['name' => 'Hentai', 'slug' => 'hentai', 'description' => 'Adult-oriented content with explicit themes'],
            ['name' => 'Dark', 'slug' => 'dark', 'description' => 'Stories with darker themes and mature content'],
            ['name' => 'Gore', 'slug' => 'gore', 'description' => 'Stories containing graphic violence and intense content'],
            ['name' => 'Other', 'slug' => 'other', 'description' => 'Stories that don\'t fit into other specific categories'],
            ['name' => 'Slice of Life', 'slug' => 'slice-of-life', 'description' => 'Stories focusing on everyday life experiences and personal growth'],
            ['name' => 'Isekai', 'slug' => 'isekai', 'description' => 'Stories about characters transported to another world'],
            ['name' => 'Fanfiction', 'slug' => 'fanfiction', 'description' => 'Stories based on existing works, characters, or universes'],
            ['name' => 'Anime / Comic', 'slug' => 'anime-comic', 'description' => 'Stories adapted from or inspired by anime and comics'],
            ['name' => 'Tragedy', 'slug' => 'tragedy', 'description' => 'Stories with dramatic and often sorrowful themes'],
            ['name' => 'War', 'slug' => 'war', 'description' => 'Stories centered around military conflicts and their impact'],
        ];

        DB::table('genres')->insertOrIgnore($genres);
    }

    public function down()
    {
        Schema::dropIfExists('genres');
    }
}