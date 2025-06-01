DROP PROCEDURE IF EXISTS [dbo].[_AddLogInstanceWorldInfo];
GO

CREATE    procedure [dbo].[_AddLogInstanceWorldInfo]
@WorldID			int,
@GameWorldLayerID	smallint,
@JID				int,
@ValueCodeName		varchar(128),
@Value				varchar(128)
as

	DECLARE @CharID INT
	DECLARE @CharRegionID INT
	SET @CharID = (SELECT TOP(1) CharID FROM SILKROAD_R_SHARD.._Char WHERE CharID = (SELECT TOP(1) CharID FROM SILKROAD_R_SHARD.._User WHERE UserJID = @JID) ORDER BY LastLogout DESC)
	SET @CharRegionID = (SELECT LatestRegion FROM SILKROAD_R_SHARD.._Char WHERE CharID = @CharID)

	declare @len_Value	int
	set @len_Value = len(@Value)
	if ( @len_Value > 0)
	begin
		insert _LogInstanceWorldInfo values( @CharRegionID, GetDate(), @GameWorldLayerID, @CharID, @ValueCodeName, @Value)
	end
	else
	begin
		insert _LogInstanceWorldInfo ( WorldID, EventTime, GameWorldLayerID, CharID, ValueCodeName128 ) values( @CharRegionID, GetDate(), @GameWorldLayerID, @CharID, @ValueCodeName )
	end

	/*
	Adding Kill Unique:
	EXEC SILKROAD_R_SHARD_LOG.._AddLogInstanceWorldInfo <RegionID>, 1, <CharID>, 'KILL_UNIQUE_MONSTER', 'MOB_CH_TIGERWOMAN'

	Adding Spawn Unique:
	EXEC SILKROAD_R_SHARD_LOG.._AddLogInstanceWorldInfo <RegionID>, 1, 0, 'SPAWN_UNIQUE_MONSTER', 'MOB_CH_TIGERWOMAN'
	*/
