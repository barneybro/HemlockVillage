<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ModelHelper
{
	/**
	 * Get the first row from a table where the value of a column is found
	 *
	 * @param Object $model The model
	 * @param string $col The column name
	 * @param int|string $val The value
	 * @return Object|null An instance of the model; null if a record can not be found
	 */
	public static function getRow($model, $col, $val)
	{
		return $model::where($col, "=", $val)->first() ?? null;
	}

	/**
	 * Get all rows from a table where the the conditions are true
	 *
	 * @param Model $model The model
	 * @param array< array<string, string, int|string> > $conditions Conditions to be matched by a "where"
	 * @return Illuminate\Database\Eloquent\Collection A collection of the model instances
	 */
	public static function getRowsWithConditions($model, $conditions)
	{
		return $model::where($conditions)->get();
	}

	/**
	 * Get the first row from a table where the conditions are true
	 *
	 * @param Model $model The model
	 * @param array< array<string, string, int|string> > $conditions Conditions to be matched by a "where"
	 * @return Model|null An instance of the model; null if a record can not be found
	 */
	public static function getRowWithConditions($model, $conditions)
	{
		return self::getRowsWithConditions($model, $conditions)->first() ?? null;
	}

	/**
	 * Get the id of the first row from a table where the value of a column is found
	 *
	 * @param Model $model The model
	 * @param string $col The column name
	 * @param int|string $val The value
	 * @return int|null The id; null if a record can not be found
	 */
	public static function getId($model, $col, $val)
	{
		return self::getRow($model, $col, $val)->id ?? null;
	}

	/**
	 * Get the id of the first row from a table where the conditions are true
	 *
	 * @param Model $model The model
	 * @param string $col The column name
	 * @param array< array<string, string, int|string> > $conditions Conditions to be matched by a "where"
	 * @return int|null The id; null if a record can not be found
	 */
	public static function getIdWithConditions($model, $conditions)
	{
		return self::getRowWithConditions($model, $conditions)->id ?? null;
	}

	/**
	 * @param int $length
	 */
	public static function getRandomString($length = 16)
	{
		return Str::random($length);
	}
}
