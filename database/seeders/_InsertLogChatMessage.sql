DROP PROCEDURE IF EXISTS [dbo].[_InsertLogChatMessage] ;
GO

CREATE PROCEDURE [dbo].[_InsertLogChatMessage]     
--	@ShardID  INT,                                           
	@ShardName		VARCHAR(64),                                           
	@CharName		NVARCHAR(64),   
	-- #ifdef SMC_CHAT_MONITERING_ADD_WHISPER  
	@TargetName		NVARCHAR(64),     
	-- #endif // SMC_CHAT_MONITERING_ADD_WHISPER    
	@ContinentName  VARCHAR(128),    
	@Comment		NVARCHAR(128)             
AS    

-- #ifdef SMC_CHAT_MONITERING_ADD_WHISPER            
	INSERT [dbo].[_LogChatMessage] VALUES ( @ShardName, GetDate(), @CharName, @TargetName, @ContinentName, @Comment )
-- #else // SMC_CHAT_MONITERING_ADD_WHISPER
	--INSERT [dbo].[_LogChatMessage] VALUES( @ShardName, GetDate(), @CharName, @ContinentName, @Comment )
-- #endif // SMC_CHAT_MONITERING_ADD_WHISPER
