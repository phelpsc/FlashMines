﻿package  {		import com.greensock.*;	import com.greensock.easing.*;		import flash.display.BlendMode;	import flash.display.DisplayObject;	import flash.display.MovieClip;	import flash.events.MouseEvent;	import flash.events.Event;		public class Tile extends MovieClip {				public var revealed : Boolean = false;		public var scored : Boolean = false;		public var neighbors : Array;		public var tile_id : uint;		public var isMine : Boolean = false;		public var isFlagged : Boolean = false;		public var bomb : Bomb;		public var flag : Flag;		public var gameOver : Boolean = false;				public function Tile() {						neighbors = new Array();						tf.visible = false;			addEventListener( MouseEvent.MOUSE_OVER, _onRollOver );			addEventListener( MouseEvent.MOUSE_OUT, _onRollOut );			addEventListener( MouseEvent.CLICK, onClick );						bomb = new Bomb();			bomb.x = 3.5;			bomb.y = 2;							flag = new Flag();			flag.x = 2;			flag.y = 3;						mouseChildren = false;			buttonMode = true;			DisplayObject(getChildAt(0)).blendMode = BlendMode.OVERLAY;		}				public function init() : void {			if (isMine) {				bomb.visible = false;				addChild(bomb);			} else {				var i : int;				var bombs : int = 0;				for (i = 0; i < neighbors.length; i++) {					if (Tile(neighbors[i]).isMine) bombs++;				}				tf.text = (bombs > 0) ? String(bombs) : "-";				if (tf.text == "-") tf.alpha = .5;			}		}				public function lose() : void {			gameOver = true;						if (isFlagged && isMine) {				//			} else if (!revealed) {				revealed = true;				DisplayObject(getChildAt(0)).blendMode = BlendMode.NORMAL;				TweenMax.to(getChildAt(0), .01, {colorTransform:{tint:0xff0000, tintAmount:0.3}});				(isMine) ? bomb.visible = true : tf.visible = true;				if (isFlagged && !isMine) {					dispatchEvent( new Event("flagRemoved", true, false) );					TweenMax.to(flag, .01, {colorTransform:{tint:0xbbbb00, tintAmount:0.8}});					tf.visible = false;				}				buttonMode = false;				removeEventListener( MouseEvent.MOUSE_OVER, _onRollOver );				removeEventListener( MouseEvent.MOUSE_OUT, _onRollOut );				removeEventListener( MouseEvent.CLICK, onClick );			}						if (revealed && isMine) bomb.visible = true;						var i : int;			for (i = 0; i < neighbors.length; i++) {				if (!Tile(neighbors[i]).gameOver) Tile(neighbors[i]).lose();			}		}				protected function _onRollOver( $e : MouseEvent ) : void {			if (revealed || isFlagged ) return;			TweenMax.to(getChildAt(0), .01, {colorTransform:{tint:0x006666, tintAmount:0.8}});			DisplayObject(getChildAt(0)).blendMode = BlendMode.NORMAL;		}				protected function _onRollOut( $e : MouseEvent = null ) : void {			if (revealed || isFlagged) return;			TweenMax.to(getChildAt(0), .4, {colorTransform:{tint:0xffffff, tintAmount:0}});			DisplayObject(getChildAt(0)).blendMode = BlendMode.OVERLAY;		}				public function onClick( $e : MouseEvent = null ) : void {			if (revealed) return;						_onRollOut();			DisplayObject(getChildAt(0)).blendMode = BlendMode.NORMAL;						if (MinesweeperBase(parent.parent).shiftPressed && !isFlagged) {				addChild(flag);				dispatchEvent( new Event("flagPlaced", true, false) );				isFlagged = true;				return;			}						if (isFlagged) {				removeChild(flag);				dispatchEvent( new Event("flagRemoved", true, false) );				isFlagged = false;				return;			}						if (isMine) {				revealed = true;				scored = true;				dispatchEvent( new Event("explosion", true, false) );				lose();				return;			}						revealed = true;			scored = true;			tf.visible = true;			dispatchEvent( new Event("revealed", true, false) );			removeEventListener( MouseEvent.MOUSE_OVER, _onRollOver );			removeEventListener( MouseEvent.MOUSE_OUT, _onRollOut );			removeEventListener( MouseEvent.CLICK, onClick );						if (tf.text == "-") {				// blank tile, reveal all neighbors.				var i : int;				for (i = 0; i < neighbors.length; i++) {					Tile(neighbors[i]).onClick();				}			}		}				public function kill() : void {			removeEventListener( MouseEvent.MOUSE_OVER, _onRollOver );			removeEventListener( MouseEvent.MOUSE_OUT, _onRollOut );			removeEventListener( MouseEvent.CLICK, onClick );		}	}	}