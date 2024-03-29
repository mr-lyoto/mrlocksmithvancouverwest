<?php

/**
 * WPSpeed - Performs several front-end optimizations for fast downloads
 *
 * @package   WPSpeed
 * @author    JExtensions Store <info@storejextensions.org>
 * @copyright Copyright (c) 2022 JExtensions Store / WPSpeed
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace WPSpeed\Core\Css;

defined( '_WPSPEED_EXEC' ) or die( 'Restricted access' );

class CssSearchObject
{
	protected $aCssRuleCriteria = array();

	protected $aCssAtRuleCriteria = array();

	protected $aCssNestedRuleNames = array();

	protected $aCssCustomRule = array();

	protected $bIsCssCommentSet = false;


	public function setCssRuleCriteria( $sCriteria )
	{
		$this->aCssRuleCriteria[] = $sCriteria;
	}

	public function getCssRuleCriteria()
	{
		return $this->aCssRuleCriteria;
	}

	public function setCssAtRuleCriteria( $sCriteria )
	{
		$this->aCssAtRuleCriteria[] = $sCriteria;
	}

	public function getCssAtRuleCriteria()
	{
		return $this->aCssAtRuleCriteria;
	}

	public function setCssNestedRuleName( $sNestedRule, $bRecurse = false, $bEmpty = false )
	{
		$this->aCssNestedRuleNames[] = array(
			'name'        => $sNestedRule,
			'recurse'     => $bRecurse,
			'empty-value' => $bEmpty
		);
	}

	public function getCssNestedRuleNames()
	{
		return $this->aCssNestedRuleNames;
	}

	public function setCssCustomRule( $sCssCustomRule )
	{
		$this->aCssCustomRule[] = $sCssCustomRule;
	}

	public function getCssCustomRule()
	{
		return $this->aCssCustomRule;
	}

	public function setCssComment()
	{
		$this->bIsCssCommentSet = true;
	}

	public function getCssComment()
	{
		if ($this->bIsCssCommentSet)
		{
			return Parser::BLOCK_COMMENT();
		}

		return false;
	}
}
