    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    protected $tableName;
    
    public function __construct()
    {
        $this->tableName = config('cartify.table', 'carts');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->defaultTableSchema();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropDefaultTable();
    }

    public function createTableSchema($tableName)
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique()->index();
            $table->boolean('isUser')->default(false);
            $table->text('content');
            $table->timestamps();
        });
    }

    public function defaultTableSchema()
    {
        $this->createTableSchema($this->tableName);
    }

    public function dropDefaultTable()
    {
        Schema::dropIfExists($this->tableName);
    }
}
