<?php

namespace Melsaka\Cartify\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getTable()
    {
        $tableName = config('cartify.table', 'carts');

        return $tableName;
    }

    public static function add(array $data): Cart
    {
    	$data = json_encode($data);
    	
    	return Cart::create([
    		'identifier' 	=> static::getIdentifier(),
    		'isUser'		=> auth()->check(),
    		'content'		=> $data
    	]);
    }

    public static function ofOwner($id)
    {
    	return Cart::where('identifier', $id)->first();
    }

    public function set(array $data): bool
    {
    	$content = json_decode($this->content, true);

	    foreach ($data as $key => $value) {
	        $keys = explode('.', $key);
	        $currentArray = &$content;

	        foreach ($keys as $subKey) {
	            if (!isset($currentArray[$subKey]) || !is_array($currentArray[$subKey])) {
	                $currentArray[$subKey] = [];
	            }
	            $currentArray = &$currentArray[$subKey];
	        }

	        $currentArray = $value;
	    }

	    $this->content = $content;

		$content = json_encode($content);

    	return $this->update(['content' => $content]);
    }

    public function unset($keysToDelete): bool
    {
    	$keysToDelete = !is_string($keysToDelete) ? $keysToDelete : [$keysToDelete];

    	$content = json_decode($this->content, true);

    	$oldContent = $content;

    	$this->deleteKeysByDotNotation($content, $keysToDelete);

    	if ($oldContent === $content) {
    		return false;
    	}

	    $this->content = $content;

		$content = json_encode($content);

    	return $this->update(['content' => $content]);
    }

    public function getContent($key = null)
    {
    	$content = json_decode($this->content, true);

    	if (is_null($key)) {
    		return $content;
    	}

    	$keys = explode('.', $key);

	    foreach ($keys as $subKey) {
	        if (!is_array($content) || !array_key_exists($subKey, $content)) {
	            return null; 
	        } 
	        
	        $content = $content[$subKey];
	    }

	    return $content;
    }

    public static function getIdentifier(): string
    {
        return auth()->check() ? auth()->id() : static::getGuestIdentifier();
    }

    public static function getGuestIdentifier()
    {
        $randomId = uniqid('cartify-', true);

        $identifier = session()->get('cartify_identifier', $randomId);

        if($identifier === $randomId) {
            session()->put('cartify_identifier', $identifier);
        }

        return $identifier;
    }

    private function deleteKeysByDotNotation(&$array, $keysToDelete) {
        foreach ($keysToDelete as $keyToDelete) {
	        $keys = explode('.', $keyToDelete);
	        $currentArray = &$array;

	        while (count($keys) > 1) {
	            $subKey = array_shift($keys);

	            if (!isset($currentArray[$subKey]) || !is_array($currentArray[$subKey])) {
	                break; // The key path is not valid
	            }

	            $currentArray = &$currentArray[$subKey];
	        }

	        $lastKey = end($keys);

	        if (isset($currentArray[$lastKey])) {
	            unset($currentArray[$lastKey]);
	        }
	    }

	    return $array;
	}
}