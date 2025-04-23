
SELECT     
	it.ID64
	,inv.Slot
	,OptLevel = it.OptLevel + (CASE WHEN adv.nOptValue > 0 THEN adv.nOptValue ELSE 0 END)
	,Adv = CASE WHEN adv.nOptValue > 0 THEN 'Advanced elixir is in effect [+' + cast(adv.nOptValue AS VARCHAR) + ']' ELSE 'Able to use Advanced elixir' END
	,ch.CharName16
	,AssocFileIcon128 = (SELECT REPLACE((REPLACE(obj.AssocFileIcon128, '\\', '/')), '.ddj', ''))
	,Country =  CASE WHEN obj.Country = 0 THEN 'Chinese' WHEN obj.Country = 1 THEN 'Europe' END
	,obj.CodeName128
	,ReqLevel = CASE WHEN obj.ReqLevel1 > 0 THEN 'Required level ' + cast(obj.ReqLevel1 AS VARCHAR) END
	,Gender = CASE WHEN inv.Slot IN (0,1,2,3,4,5) AND (ch.RefObjID BETWEEN 1907 AND 1919 OR ch.RefObjID BETWEEN 14875 AND 14887) THEN 'Male' WHEN inv.Slot IN (0,1,2,3,4,5) AND (ch.RefObjID BETWEEN 1920 AND 1932 OR ch.RefObjID BETWEEN 14888 AND 14900) THEN 'Female' END
	,SealType = CASE WHEN item.ItemClass > 30 AND (obj.CodeName128 LIKE '%A_RARE%' OR obj.CodeName128 LIKE '%SET_A_RARE%' OR obj.CodeName128 LIKE '%SET_B_RARE%') THEN 'Seal of Nova' WHEN obj.CodeName128 LIKE '%A_RARE%' THEN 'Seal of Star' WHEN obj.CodeName128 LIKE '%B_RARE%' THEN 'Seal of Moon' WHEN obj.CodeName128 LIKE '%C_RARE%' THEN 'Seal of Sun' ELSE 'Normal' END
	,SetName = CASE WHEN obj.CodeName128 LIKE '%SET_A_RARE%' AND inv.Slot = 6 THEN 'Power' WHEN obj.CodeName128 LIKE '%SET_B_RARE%' AND inv.Slot = 6 THEN 'Fight' WHEN obj.CodeName128 LIKE '%SET_A_RARE%' AND inv.Slot = 7 THEN 'Protection' WHEN obj.CodeName128 LIKE '%SET_B_RARE%' AND inv.Slot = 7 THEN 'Guard' WHEN obj.CodeName128 LIKE '%SET_A_RARE%' AND inv.Slot IN(0,1,2,3,4,5) THEN 'Destruction' WHEN obj.CodeName128 LIKE '%SET_B_RARE%' AND inv.Slot IN(0,1,2,3,4,5) THEN 'Immortality' WHEN obj.CodeName128 LIKE '%SET_A_RARE%' AND inv.Slot IN(9,10,11,12) THEN 'Myth' WHEN obj.CodeName128 LIKE '%SET_B_RARE%' AND inv.Slot IN(9,10,11,12) THEN 'Legend' ELSE NULL END 
	,Degree = 'Degree: ' + cast(CEILING(item.ItemClass / 3.0) AS VARCHAR) + ' degrees' 
	--,ItemType = 'Sort of item: '
	,MPart = CASE WHEN inv.Slot = 0 THEN 'Mounting part: Head' WHEN inv.Slot = 1 THEN 'Mounting part: Chest' WHEN inv.Slot = 2 THEN 'Mounting part: Shoulder' WHEN inv.Slot = 3 THEN 'Mounting part: Hands' WHEN inv.Slot = 4 THEN 'Mounting part: Legs' WHEN inv.Slot = 5 THEN 'Mounting part: Foot' END 
	,ItemLocation = CASE WHEN inv.Slot = 0 THEN 'left' WHEN inv.Slot = 1 THEN 'left' WHEN inv.Slot = 2 THEN 'right' WHEN inv.Slot = 3 THEN 'right' WHEN inv.Slot = 4 THEN 'left' WHEN inv.Slot = 5 THEN 'right' WHEN inv.Slot = 6 THEN 'left' WHEN inv.Slot = 7 THEN 'right' WHEN inv.Slot = 8 THEN 'right' WHEN inv.Slot = 9 THEN 'left' WHEN inv.Slot = 10 THEN 'right' WHEN inv.Slot = 11 THEN 'left' WHEN inv.Slot = 12 THEN 'right' END
	,Itemrow = CASE WHEN inv.Slot = 0 THEN 3 WHEN inv.Slot = 1 THEN 5 WHEN inv.Slot = 2 THEN 4 WHEN inv.Slot = 3 THEN 6 WHEN inv.Slot = 4 THEN 7 WHEN inv.Slot = 5 THEN 8 WHEN inv.Slot = 6 THEN 1 WHEN inv.Slot = 7 THEN 2 WHEN inv.Slot = 8 THEN 13 WHEN inv.Slot = 9 THEN 9 WHEN inv.Slot = 10 THEN 10 WHEN inv.Slot = 11 THEN 11 WHEN inv.Slot = 12 THEN 12 END
	,MOptNum = CASE WHEN item.MaxMagicOptCount > 0 THEN 'Max. no. of magic options: ' + cast(item.MaxMagicOptCount AS VARCHAR) + ' Unit' END
	,it.Data

	--Whites
	,PAtack = CASE WHEN item.PAttackMin_L > 0 AND item.PAttackMax_L > 0 THEN 'Phy. atk. pwr. ' + CAST(CAST((item.PAttackMin_L + item.PAttackInc * OptLevel) + ((item.PAttackMin_U - item.PAttackMin_L) * (FLOOR(((it.Variance / POWER(32, 4)) & 0x1F) * 3.23)) / 100) AS INT) AS VARCHAR) + ' ~ ' + CAST(CAST((item.PAttackMax_L + item.PAttackInc * OptLevel) + ((item.PAttackMax_U - item.PAttackMax_L) * (FLOOR(((it.Variance / POWER(32, 4)) & 0x1F) * 3.23)) / 100) AS INT) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 4)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,MAtack = CASE WHEN item.MAttackMin_L > 0 AND item.MAttackMax_L > 0 THEN 'Mag. atk. pwr. ' + CAST(CAST((item.MAttackMin_L + item.MAttackInc * OptLevel) + ((item.MAttackMin_U - item.MAttackMin_L) * (FLOOR(((it.Variance / POWER(32, 5)) & 0x1F) * 3.23)) / 100) AS INT) AS VARCHAR) + ' ~ ' + CAST(CAST((item.MAttackMax_L + item.MAttackInc * OptLevel) + ((item.MAttackMax_U - item.MAttackMax_L) * (FLOOR(((it.Variance / POWER(32, 5)) & 0x1F) * 3.23)) / 100) AS INT) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 5)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,PDefance = CASE WHEN item.PD_L > 0 THEN 'Phy. def. pwr. ' + CAST(ROUND((item.PD_L + item.PDInc * OptLevel) + ((item.PD_U - item.PD_L) * (FLOOR(((it.Variance / POWER(32, 3)) & 0x1F) * 3.23)) / 100), 1) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 3)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,MDefance = CASE WHEN item.MD_L > 0 THEN 'Mag. def. pwr. ' + CAST(ROUND((item.MD_L + item.MDInc * OptLevel) + ((item.MD_U - item.MD_L) * (FLOOR(((it.Variance / POWER(32, 4)) & 0x1F) * 3.23)) / 100), 1) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 4)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,Durability = CASE WHEN item.Dur_U > 0 THEN 'Durability ' + CAST(it.Data AS VARCHAR) + '/' + CAST(it.Data AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 0)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,BlockRate = CASE WHEN item.BR_L > 0 THEN 'Block Rate ' + CAST(CAST((item.BR_L) + ((item.BR_U - item.BR_L) * (FLOOR(((it.Variance / POWER(32, 3)) & 0x1F) * 3.23)) / 100) AS INT) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 3)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,AtackDist = CASE WHEN item.[Range] > 0 THEN 'Attack distance ' + CAST(CAST(CAST(item.[Range] AS FLOAT) / 10 AS DECIMAL(8, 1)) AS VARCHAR) + ' m' END
	,AtackRate = CASE WHEN item.HR_L > 0 THEN 'Attack rate ' + CAST(CAST((item.HR_L + item.HRInc * OptLevel) + ((item.HR_U - item.HR_L) * (FLOOR(((it.Variance / POWER(32, 3)) & 0x1F) * 3.23)) / 100) AS INT) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 3)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,Critical = CASE WHEN item.CHR_L > 0 THEN 'Critical ' + CAST(CAST((item.CHR_L) + ((item.CHR_U - item.CHR_L) * (FLOOR(((it.Variance / POWER(32, 6)) & 0x1F) * 3.23)) / 100) AS INT) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 6)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,ParryRate = CASE WHEN item.ER_L > 0 THEN 'Parry rate ' + CAST(CAST((item.ER_L + item.ERInc * OptLevel) + ((item.ER_U - item.ER_L) * (FLOOR(((it.Variance / POWER(32, 5)) & 0x1F) * 3.23)) / 100) AS INT) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 5)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,PReinforceWep = CASE WHEN item.PAStrMin_L > 0 AND item.PAStrMax_L > 0 THEN 'Phy. reinforce ' + CAST(CAST(CAST((item.PAStrMin_L) + ((item.PAStrMin_U - item.PAStrMin_L) * (FLOOR(((it.Variance / POWER(32, 1)) & 0x1F) * 3.23)) / 100) AS FLOAT) / 10 AS DECIMAL(8, 1)) AS VARCHAR) + ' ~ ' + CAST(CAST(CAST((item.PAStrMax_L) + ((item.PAStrMax_U - item.PAStrMax_L) * (FLOOR(((it.Variance / POWER(32, 1)) & 0x1F) * 3.23)) / 100) AS FLOAT) / 10 AS DECIMAL(8, 1)) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 1)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,MReinforceWep = CASE WHEN item.MAInt_Min_L > 0 AND item.MAInt_Max_L > 0 THEN 'Mag. reinforce ' + CAST(CAST(CAST((item.MAInt_Min_L) + ((item.MAInt_Min_U - item.MAInt_Min_L) * (FLOOR(((it.Variance / POWER(32, 2)) & 0x1F) * 3.23)) / 100) AS FLOAT) / 10 AS DECIMAL(8, 1)) AS VARCHAR) + ' ~ ' + CAST(CAST(CAST((item.MAInt_Max_L) + ((item.MAInt_Max_U - item.MAInt_Max_L) * (FLOOR(((it.Variance / POWER(32, 2)) & 0x1F) * 3.23)) / 100) AS FLOAT) / 10 AS DECIMAL(8, 1)) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 2)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,PReinforceSet = CASE WHEN item.PDStr_L > 0 THEN 'Phy. reinforce ' + CAST(CAST(CAST((item.PDStr_L) + ((item.PDStr_U - item.PDStr_L) * (FLOOR(((it.Variance / POWER(32, 1)) & 0x1F) * 3.23)) / 100) AS FLOAT) / 10 AS DECIMAL(8, 1)) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 1)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,MReinforceSet = CASE WHEN item.MDInt_L > 0 THEN 'Mag. reinforce ' + CAST(CAST(CAST((item.MDInt_L) + ((item.MDInt_U - item.MDInt_L) * (FLOOR(((it.Variance / POWER(32, 2)) & 0x1F) * 3.23)) / 100) AS FLOAT) / 10 AS DECIMAL(8, 1)) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 2)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,Pabsorp = CASE WHEN item.PAR_L > 0 THEN 'Phy. absorption ' + CAST(ROUND((item.PAR_L + item.PARInc * OptLevel) + ((item.PAR_U - item.PAR_L) * (FLOOR(((it.Variance / POWER(32, 0)) & 0x1F) * 3.23)) / 100), 1) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 0)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END
	,Mabsorp = CASE WHEN item.MAR_L > 0 THEN 'Mag. absorption ' + CAST(ROUND((item.MAR_L + item.MARInc * OptLevel) + ((item.MAR_U - item.MAR_L) * (FLOOR(((it.Variance / POWER(32, 1)) & 0x1F) * 3.23)) / 100), 1) AS VARCHAR) + ' (+' + CAST(FLOOR(((it.Variance / POWER(32, 1)) & 0x1F) * 3.23) AS VARCHAR) + '%)' END 
	
	--Blues
	,it.MagParamNum
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam1 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam1 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam2 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam2 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam3 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam3 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam4 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam4 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam5 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam5 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam6 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam6 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam7 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam7 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam8 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam8 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam9 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam9 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam10 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam10 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam11 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam11 AS BIGINT)), 5, 4) AS VARBINARY)))
	,(SELECT MOpt.MOptName128 + ': ' + CAST(CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam12 AS BIGINT)), 1, 4) AS VARBINARY)) AS VARCHAR)FROM _RefMagicOpt MOpt WHERE MOpt.ID = CONVERT(INT, CAST(SUBSTRING(CONVERT(VARBINARY(8), CAST(it.MagParam12 AS BIGINT)), 5, 4) AS VARBINARY)))

FROM 
	[_Char] AS ch
	LEFT JOIN [dbo].[_Inventory] AS inv ON ch.CharID = inv.CharID
	--LEFT JOIN [dbo].[_InventoryForAvatar] AS inv ON ch.CharID = inv.CharID
	--LEFT JOIN [dbo].[_TradeEquipInventory] AS inv ON ch.CharID = inv.CharID

	LEFT JOIN [dbo].[_Items] AS it ON inv.ItemID = it.ID64
	LEFT JOIN [dbo].[_RefObjCommon] AS obj ON it.RefItemID = obj.ID
	LEFT JOIN [dbo].[_RefObjItem] AS item ON obj.Link = item.ID
	LEFT JOIN [dbo].[_BindingOptionWithItem] AS adv ON it.ID64 = adv.nItemDBID AND adv.bOptType = 2
	
WHERE 
	ch.CharName16 = 'm1xawy'
	AND (inv.Slot BETWEEN 0 AND 12) 
	AND inv.Slot != 8

ORDER BY 
	Itemrow ASC        
